<?php

declare(strict_types = 1);

namespace Gogart\Http\Controller;

use Gogart\Application\Product\Command\AddProductCommand;
use Gogart\Application\Product\Command\ChangeProductPriceCommand;
use Gogart\Application\Product\Command\ChangeProductTitleCommand;
use Gogart\Application\Product\Command\RemoveProductCommand;
use Gogart\Application\Product\Data\PriceData;
use Gogart\Application\Product\Data\ProductData;
use Gogart\Application\Product\Data\TitleData;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\CommandDispatchException;
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
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param CommandBus $commandBus
     * @param SerializerInterface $serializer
     */
    public function __construct(
        CommandBus $commandBus,
        SerializerInterface $serializer
    ) {
        $this->commandBus = $commandBus;
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
     * @param array $data
     *
     * @return string
     */
    private function serialize(array $data): string
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
}
