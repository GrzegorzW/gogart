<?php

declare(strict_types = 1);

namespace Gogart\Application\Cart\Query\ViewModel;

use Gogart\Application\Product\Query\ViewModel\ProductListView;

class CartView
{
    /**
     * @var string
     */
    private $cartId;

    /**
     * @var ProductListView
     */
    private $data;

    /**
     * @var string|null
     */
    private $totalPrice;

    /**
     * @param string $cartId
     * @param ProductListView $products
     * @param null|string $totalPrice
     */
    public function __construct(
        string $cartId,
        ProductListView $products,
        ?string $totalPrice
    ) {
        $this->cartId = $cartId;
        $this->data = $products;
        $this->totalPrice = $totalPrice;
    }
}
