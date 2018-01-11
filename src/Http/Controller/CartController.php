<?php

declare(strict_types = 1);

namespace Gogart\Http\Controller;

use Gogart\Application\Cart\Command\AddCartCommand;
use Gogart\Application\Cart\Command\AddProductToCartCommand;
use Gogart\Application\Cart\Command\RemoveProductFromCartCommand;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\CommandDispatchException;
use Prooph\ServiceBus\QueryBus;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CartController
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
     * @return Response
     *
     * @throws CommandDispatchException
     * @throws BadRequestHttpException
     */
    public function addCart(): Response
    {
        $command = new AddCartCommand([
            'id' => Uuid::uuid4()
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
     * @param UuidInterface $cartId
     * @param UuidInterface $productId
     *
     * @return Response
     *
     * @throws CommandDispatchException
     */
    public function addProduct(UuidInterface $cartId, UuidInterface $productId): Response
    {
        $command = new AddProductToCartCommand([
            'cartId' => $cartId,
            'productId' => $productId
        ]);

        $this->commandBus->dispatch($command);

        return new JsonResponse('', Response::HTTP_NO_CONTENT, [], true);
    }



    /**
     * @param UuidInterface $cartId
     * @param UuidInterface $productId
     *
     * @return Response
     *
     * @throws CommandDispatchException
     */
    public function removeProduct(UuidInterface $cartId, UuidInterface $productId): Response
    {
        $command = new RemoveProductFromCartCommand([
            'cartId' => $cartId,
            'productId' => $productId
        ]);

        $this->commandBus->dispatch($command);

        return new JsonResponse('', Response::HTTP_NO_CONTENT, [], true);
    }
}
