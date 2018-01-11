<?php

declare(strict_types = 1);

namespace Gogart\Application\Cart\Command\Handler;

use Gogart\Application\Cart\Command\AddCartCommand;
use Gogart\Model\Cart\Cart;
use Gogart\Model\Cart\Repository\CartRepositoryInterface;
use Gogart\Model\Product\Product;
use Gogart\Model\Product\Title;
use Gogart\Model\Shared\Amount;
use Gogart\Model\Shared\Currency;
use Gogart\Model\Shared\Price;

class AddCartHandler
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * @param AddCartCommand $command
     *
     */
    public function __invoke(AddCartCommand $command): void
    {
        $cart = Cart::createCart($command->getId());

        $this->cartRepository->save($cart);
    }
}
