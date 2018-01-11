<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Command\Handler;

use Gogart\Application\Product\Command\ChangeProductTitleCommand;
use Gogart\Model\Product\Exception\ProductNotFound;
use Gogart\Model\Product\Repository\ProductRepositoryInterface;
use Gogart\Model\Product\Title;

class ChangeProductTitleHandler
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
     * @param ChangeProductTitleCommand $command
     *
     * @throws \InvalidArgumentException
     * @throws ProductNotFound
     */
    public function __invoke(ChangeProductTitleCommand $command): void
    {
        $product = $this->productRepository->get($command->getId());

        $newTitle = new Title($command->getTitleData()->getTitle());

        $product->changeTitle($newTitle);

        $this->productRepository->save($product);
    }
}
