<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Command\Handler;

use Gogart\Application\Product\Command\RemoveProductCommand;
use Gogart\Model\Product\Exception\ProductAlreadyRemoved;
use Gogart\Model\Product\Exception\ProductNotFound;
use Gogart\Model\Product\Repository\ProductRepositoryInterface;

class RemoveProductHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param RemoveProductCommand $command
     *
     * @throws ProductNotFound
     * @throws ProductAlreadyRemoved
     */
    public function __invoke(RemoveProductCommand $command): void
    {
        $product = $this->productRepository->get($command->getId());

        $product->remove();

        $this->productRepository->save($product);
    }
}
