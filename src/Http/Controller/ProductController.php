<?php

declare(strict_types = 1);

namespace Gogart\Http\Controller;

use Gogart\Application\Product\Command\AddProductCommand;
use Gogart\Application\Product\Command\ChangeProductPriceCommand;
use Gogart\Application\Product\Command\ChangeProductTitleCommand;
use Gogart\Application\Product\Command\RemoveProductCommand;
use Gogart\Application\Product\Data\PaginationData;
use Gogart\Application\Product\Data\PriceData;
use Gogart\Application\Product\Data\ProductData;
use Gogart\Application\Product\Data\TitleData;
use Gogart\Application\Product\Query\ListProductQuery;
use Gogart\Application\Product\Query\ViewModel\PaginatedProductListView;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\CommandDispatchException;
use Prooph\ServiceBus\Exception\RuntimeException;
use Prooph\ServiceBus\QueryBus;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param CommandBus $commandBus
     * @param QueryBus $queryBus
     * @param SerializerInterface $serializer
     */
    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        SerializerInterface $serializer
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->serializer = $serializer;
    }

    /**
     * @param ProductData $data
     *
     * @return Response
     *
     * @throws CommandDispatchException
     * @throws BadRequestHttpException
     */
    public function add(ProductData $data): Response
    {
        $command = new AddProductCommand([
            'id' => Uuid::uuid4(),
            'data' => $data
        ]);

        $this->commandBus->dispatch($command);

        $responseData = [
            'id' => $command->getId()->toString()
        ];

        return new JsonResponse($this->serialize($responseData), Response::HTTP_OK, [], true);
    }

    /**
     * @param mixed $data
     *
     * @return string
     */
    private function serialize($data): string
    {
        $serializationContext = new SerializationContext();
        $serializationContext->setSerializeNull(true);

        return $this->serializer->serialize(
            $data,
            'json',
            $serializationContext
        );
    }

    /**
     * @param UuidInterface $productId
     * @param TitleData $data
     *
     * @return Response
     *
     * @throws CommandDispatchException
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function changeTitle(UuidInterface $productId, TitleData $data): Response
    {
        $command = new ChangeProductTitleCommand([
            'id' => $productId,
            'data' => $data
        ]);

        $this->commandBus->dispatch($command);

        return new JsonResponse('', Response::HTTP_NO_CONTENT, [], true);
    }

    /**
     * @param UuidInterface $productId
     * @param PriceData $data
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     * @throws CommandDispatchException
     * @throws BadRequestHttpException
     */
    public function changePrice(UuidInterface $productId, PriceData $data): Response
    {
        $command = new ChangeProductPriceCommand([
            'id' => $productId,
            'data' => $data
        ]);

        $this->commandBus->dispatch($command);

        return new JsonResponse('', Response::HTTP_NO_CONTENT, [], true);
    }

    /**
     * @param UuidInterface $productId
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     * @throws CommandDispatchException
     * @throws BadRequestHttpException
     */
    public function remove(UuidInterface $productId): Response
    {
        $command = new RemoveProductCommand([
            'id' => $productId
        ]);

        $this->commandBus->dispatch($command);

        return new JsonResponse('', Response::HTTP_NO_CONTENT, [], true);
    }

    /**
     * @param PaginationData $data
     *
     * @return Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws RuntimeException
     */
    public function list(PaginationData $data): Response
    {
        $response = null;

        $query = new ListProductQuery($data);

        $this->queryBus
            ->dispatch($query)
            ->then(
                function (PaginatedProductListView $view) use (&$response) {
                    $data = $this->serialize($view);

                    $response = new JsonResponse($data, Response::HTTP_OK, [], true);
                }
            );

        if ($response === null) {
            throw new BadRequestHttpException('Unable to list products');
        }

        return $response;
    }
}
