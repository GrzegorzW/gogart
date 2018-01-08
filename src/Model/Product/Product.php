<?php

declare(strict_types = 1);

namespace Gogart\Model\Product;

use Gogart\Model\Product\Exception\ProductAlreadyRemoved;
use Gogart\Model\Shared\Price;
use Ramsey\Uuid\UuidInterface;

class Product
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var Title
     */
    private $title;

    /**
     * @var Price
     */
    private $price;

    /**
     * @var
     */
    private $removedAt;

    /**
     * @param UuidInterface $id
     * @param Title $title
     * @param Price $price
     */
    public function __construct(UuidInterface $id, Title $title, Price $price)
    {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @throws ProductAlreadyRemoved
     */
    public function removeProduct(): void
    {
        if ($this->removedAt !== null) {
            throw new ProductAlreadyRemoved('Product already removed');
        }

        $this->removedAt = new \DateTimeImmutable();
    }

    /**
     * @param Price $newPrice
     */
    public function changePrice(Price $newPrice): void
    {
        if ($this->price->equals($newPrice)) {
            return;
        }

        $this->price = $newPrice;
    }

    /**
     * @param Title $newTitle
     */
    public function changeTitle(Title $newTitle): void
    {
        if ($this->title->equals($newTitle)) {
            return;
        }

        $this->title = $newTitle;
    }
}
