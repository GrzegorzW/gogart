<?php

declare(strict_types = 1);

namespace Gogart\Http\Controller;

use Gogart\Application\Cart\Command\AddCartCommand;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\CommandDispatchException;
use Prooph\ServiceBus\QueryBus;
use Ramsey\Uuid\Uuid;
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
    public function add(): Response
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
}
