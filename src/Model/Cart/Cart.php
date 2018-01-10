<?php

declare(strict_types = 1);

namespace Gogart\Model\Cart;

use Gogart\Model\Cart\Event\CartCreated;
use Gogart\Model\Cart\Event\ProductAddedToCart;
use Gogart\Model\Cart\Event\ProductRemovedFromCart;
use Gogart\Model\Cart\Exception\CartSizeLimitReachedException;
use Gogart\Model\Product\Exception\ProductNotFoundInCartException;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Cart extends AggregateRoot
{
    private const CART_SIZE_LIMIT = 3;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var []
     */
    private $products;

    /**
     * @param UuidInterface $id
     *
     * @return Cart
     */
    public static function createCart(UuidInterface $id): Cart
    {
        $instance = new self();

        $aggregateChanged = CartCreated::occur(
            $id->toString()
        );

        $instance->recordThat($aggregateChanged);

        return $instance;
    }

    /**
     * @param UuidInterface $productId
     *
     * @throws CartSizeLimitReachedException
     */
    public function addProduct(UuidInterface $productId): void
    {
        if ($this->isProductInCart($productId) === true) {
            return;
        }

        if (\count($this->products) === self::CART_SIZE_LIMIT) {
            throw new CartSizeLimitReachedException('Limit reached');
        }

        $aggregateChanged = ProductAddedToCart::occur(
            $this->id->toString(),
            [
                'productId' => $productId->toString()
            ]
        );

        $this->recordThat($aggregateChanged);
    }

    /**
     * @param UuidInterface $productId
     *
     * @return bool
     */
    private function isProductInCart(UuidInterface $productId): bool
    {
        return \in_array($productId->toString(), $this->products, true);
    }

    /**
     * @param UuidInterface $productId
     *
     * @throws ProductNotFoundInCartException
     */
    public function removeProduct(UuidInterface $productId): void
    {
        if ($this->isProductInCart($productId) === false) {
            throw new ProductNotFoundInCartException('Product not in cart');
        }

        $aggregateChanged = ProductRemovedFromCart::occur(
            $this->id->toString(),
            [
                'productId' => $productId->toString()
            ]
        );

        $this->recordThat($aggregateChanged);
    }

    /**
     * @return string
     */
    protected function aggregateId(): string
    {
        return $this->id->toString();
    }

    /**
     * @param AggregateChanged $event
     *
     * @throws \InvalidArgumentException
     */
    protected function apply(AggregateChanged $event): void
    {
        $this->id = Uuid::fromString($event->aggregateId());

        switch (\get_class($event)) {
            case CartCreated::class:
                $this->applyProductCreated();
                break;
            case ProductAddedToCart::class:
                $this->applyProductAddedToCart($event);
                break;
            case ProductRemovedFromCart::class:
                $this->applyProductRemovedFromCart($event);
                break;
        }
    }

    /**
     * Cart instance initialization
     */
    private function applyProductCreated(): void
    {
        $this->products = [];
    }

    /**
     * @param ProductAddedToCart $event
     */
    private function applyProductAddedToCart(ProductAddedToCart $event): void
    {
        $this->products[] = $event->getProductId();
    }

    /**
     * @param ProductRemovedFromCart $event
     */
    private function applyProductRemovedFromCart(ProductRemovedFromCart $event): void
    {
        $productKeyDelete = array_search($event->getProductId(), $this->products, true);

        if ($productKeyDelete !== false) {
            unset($this->products[$productKeyDelete]);
        }
    }
}
