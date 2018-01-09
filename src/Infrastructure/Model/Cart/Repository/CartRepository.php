<?php

declare(strict_types = 1);

namespace Gogart\Infrastructure\Model\Cart\Repository;

use Gogart\Model\Cart\Cart;
use Gogart\Model\Cart\Repository\CartRepositoryInterface;
use Gogart\Model\Product\Exception\CartNotFoundException;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Ramsey\Uuid\UuidInterface;

class CartRepository extends AggregateRepository implements CartRepositoryInterface
{
    /**
     * @param Cart $cart
     */
    public function save(Cart $cart): void
    {
        $this->saveAggregateRoot($cart);
    }

    /**
     * @param UuidInterface $cartId
     *
     * @return Cart
     *
     * @throws CartNotFoundException
     */
    public function get(UuidInterface $cartId): Cart
    {
        $cart = $this->getAggregateRoot($cartId->toString());

        if (!$cart instanceof Cart) {
            throw new CartNotFoundException(sprintf('Cart %s not found', $cartId->toString()));
        }

        return $cart;
    }
}
