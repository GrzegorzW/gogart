<?php

declare(strict_types = 1);

namespace Gogart\Model\Cart;

use Gogart\Model\Cart\Event\CartCreated;
use Gogart\Model\Cart\Event\ProductAddedToCart;
use Gogart\Model\Cart\Event\ProductRemovedFromCart;
use Gogart\Model\Product\Exception\ProductNotFoundInCart;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Cart extends AggregateRoot
{
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
     */
    public function createCart(UuidInterface $id): void
    {
        $instance = new self();

        $aggregateChanged = CartCreated::occur(
            $id->toString()
        );

        $instance->recordThat($aggregateChanged);
    }

    /**
     * @param UuidInterface $productId
     */
    public function addProduct(UuidInterface $productId): void
    {
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
     * @throws ProductNotFoundInCart
     */
    public function removeProduct(UuidInterface $productId): void
    {
        if ($this->isProductInCart($productId) === false) {
            throw new ProductNotFoundInCart('Product not in cart');
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
     * @param UuidInterface $productId
     *
     * @return bool
     */
    private function isProductInCart(UuidInterface $productId): bool
    {
        return isset($this->products[$productId->toString()]);
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
        $productId = $event->getProductId();
        $amountOfProduct = 1;

        if (isset($this->products[$productId])) {
            $amountOfProduct = $this->products[$productId] + 1;
        }

        $this->products[$productId] = $amountOfProduct;
    }

    /**
     * @param ProductRemovedFromCart $event
     */
    private function applyProductRemovedFromCart(ProductRemovedFromCart $event): void
    {
        $productId = $event->getProductId();

        $amountOfProduct = $this->products[$productId] ?? null;

        if (!$amountOfProduct) {
            return;
        }

        $this->products[$productId]--;

        if ($amountOfProduct === 0) {
            unset($this->products[$productId]);
        }
    }
}
