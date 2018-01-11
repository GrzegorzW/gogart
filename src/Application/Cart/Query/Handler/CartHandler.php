<?php

declare(strict_types = 1);

namespace Gogart\Application\Cart\Query\Handler;

use Gogart\Application\Cart\Query\CartQuery;
use Gogart\Application\Cart\Query\Exception\CartNotFoundException;
use Gogart\Application\Cart\Query\ReadRepository\CartReadRepositoryInterface;
use React\Promise\Deferred;

class CartHandler
{
    /**
     * @var CartReadRepositoryInterface
     */
    private $cartReadRepository;

    /**
     * @param CartReadRepositoryInterface $cartReadRepository
     */
    public function __construct(CartReadRepositoryInterface $cartReadRepository)
    {
        $this->cartReadRepository = $cartReadRepository;
    }

    /**
     * @param CartQuery $query
     * @param Deferred $deferred
     *
     * @throws CartNotFoundException
     */
    public function __invoke(CartQuery $query, Deferred $deferred)
    {
        $cart = $this->cartReadRepository->get($query->getCartId());

        $deferred->resolve($cart);
    }
}
