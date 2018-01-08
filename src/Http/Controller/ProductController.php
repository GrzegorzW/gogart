<?php

declare(strict_types = 1);

namespace Gogart\Http\Controller;

use Gogart\Application\Product\Command\AddProductCommand;
use Gogart\Application\Product\Data\ProductData;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Prooph\ServiceBus\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
     * @param ProductData $productData
     *
     * @return Response
     */
    public function add(ProductData $productData): Response
    {
        $command = new AddProductCommand([
            'id' => Uuid::uuid4(),
            'data' => $productData
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
}
