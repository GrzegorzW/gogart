<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Command\Handler;

use Gogart\Application\Product\Command\AddProductCommand;
use Gogart\Model\Product\Product;
use Gogart\Model\Product\Repository\ProductRepositoryInterface;
use Gogart\Model\Product\Title;
use Gogart\Model\Shared\Amount;
use Gogart\Model\Shared\Currency;
use Gogart\Model\Shared\Price;

class AddProductHandler
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
     * @param AddProductCommand $command
     *
     * @throws \InvalidArgumentException
     */
    public function __invoke(AddProductCommand $command): void
    {
        $productData = $command->getProductData();

        $product = new Product(
            $command->getId(),
            new Title($productData->getTitle()),
            new Price(
                new Amount($productData->getPriceAmount()),
                new Currency($productData->getPriceCurrency())
            )
        );

        $this->productRepository->save($product);
    }
}
