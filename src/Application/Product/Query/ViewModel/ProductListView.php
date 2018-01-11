<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Query\ViewModel;

class ProductListView
{
    /**
     * @var ProductView[]
     */
    private $products;

    /**
     * @param ProductView[]
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function calculateTotalPrice(): ?string
    {
        if (\count($this->products) === 0) {
            return null;
        }

        $totalAmount = 0;
        $expectedCurrency = $this->products[0]->getPriceCurrency();

        foreach ($this->products as $product) {
            if ($product->getPriceCurrency() !== $expectedCurrency) {
                throw new \InvalidArgumentException('Cannot calculate total price');
            }

            $totalAmount += $product->getPriceAmount();
        }

        return sprintf('%0.2f %s', $totalAmount / 100, $expectedCurrency);
    }
}
