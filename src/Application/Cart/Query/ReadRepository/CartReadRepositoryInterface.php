<?php

declare(strict_types = 1);

namespace Gogart\Application\Cart\Query\ReadRepository;

use Gogart\Application\Cart\Query\Exception\CartNotFoundException;
use Gogart\Application\Cart\Query\ViewModel\CartView;
use Ramsey\Uuid\UuidInterface;

interface CartReadRepositoryInterface
{
    /**
     * @param UuidInterface $cartId
     *
     * @return CartView
     *
     * @throws CartNotFoundException
     */
    public function get(UuidInterface $cartId): CartView;
}
