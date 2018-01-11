<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Command\Handler;

use Gogart\Application\Product\Command\ChangeProductPriceCommand;
use Gogart\Model\Product\Exception\ProductNotFound;
use Gogart\Model\Product\Repository\ProductRepositoryInterface;
use Gogart\Model\Shared\Amount;
use Gogart\Model\Shared\Currency;
use Gogart\Model\Shared\Price;

class ChangeProductPriceHandler
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
     * @param ChangeProductPriceCommand $command
     *
     * @throws \InvalidArgumentException
     * @throws ProductNotFound
     */
    public function __invoke(ChangeProductPriceCommand $command): void
    {
        $product = $this->productRepository->get($command->getId());

        $priceData = $command->getTitleData();
        $newPrice = new Price(
            new Amount($priceData->getPriceAmount()),
            new Currency($priceData->getPriceCurrency())
        );

        $product->changePrice($newPrice);

        $this->productRepository->save($product);
    }
}
