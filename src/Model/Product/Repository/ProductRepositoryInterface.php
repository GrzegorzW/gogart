<?php

declare(strict_types = 1);

namespace Gogart\Model\Product\Repository;

use Gogart\Model\Product\Exception\ProductNotFound;
use Gogart\Model\Product\Product;
use Ramsey\Uuid\UuidInterface;

interface ProductRepositoryInterface
{
    /**
     * @param Product $product
     */
    public function save(Product $product): void;

    /**
     * @param UuidInterface $productId
     *
     * @return Product
     *
     * @throws ProductNotFound
     */
    public function get(UuidInterface $productId): Product;
}
