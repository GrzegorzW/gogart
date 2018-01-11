<?php

declare(strict_types = 1);

namespace Gogart\Model\Cart\Repository;

use Gogart\Model\Cart\Cart;
use Gogart\Model\Product\Exception\CartNotFoundException;
use Ramsey\Uuid\UuidInterface;

interface CartRepositoryInterface
{
    /**
     * @param Cart $cart
     */
    public function save(Cart $cart): void;

    /**
     * @param UuidInterface $cartId
     *
     * @return Cart
     *
     * @throws CartNotFoundException
     */
    public function get(UuidInterface $cartId): Cart;
}
