<?php

declare(strict_types = 1);

namespace Gogart\Application\Cart\Command\Handler;

use Gogart\Application\Cart\Command\AddProductToCartCommand;
use Gogart\Model\Cart\Repository\CartRepositoryInterface;
use Gogart\Model\Product\Exception\CartNotFoundException;
use Gogart\Model\Cart\Exception\CartSizeLimitReachedException;
use Gogart\Model\Product\Exception\ProductNotFound;
use Gogart\Model\Product\Repository\ProductRepositoryInterface;

class AddProductToCartHandler
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param CartRepositoryInterface $cartRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param AddProductToCartCommand $command
     *
     * @throws CartNotFoundException
     * @throws ProductNotFound
     * @throws CartSizeLimitReachedException
     */
    public function __invoke(AddProductToCartCommand $command): void
    {
        $cart = $this->cartRepository->get($command->getCartId());

        $product = $this->productRepository->get($command->getProductId());

        $cart->addProduct($product->getId());

        $this->cartRepository->save($cart);
    }
}
