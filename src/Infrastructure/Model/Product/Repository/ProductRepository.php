<?php

declare(strict_types = 1);

namespace Gogart\Infrastructure\Model\Product\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Gogart\Model\Product\Exception\ProductNotFound;
use Gogart\Model\Product\Product;
use Gogart\Model\Product\Repository\ProductRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

class ProductRepository extends EntityRepository implements ProductRepositoryInterface
{
    /**
     * @param UuidInterface $productId
     *
     * @return Product
     *
     * @throws ProductNotFound
     */
    public function get(UuidInterface $productId): Product
    {
        $restaurant = $this->find($productId->toString());

        if (!$restaurant instanceof Product) {
            throw new ProductNotFound(sprintf('Product %s not found', $productId->toString()));
        }

        return $restaurant;
    }

    /**
     * @param Product $product
     *
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     */
    public function save(Product $product): void
    {
        $em = $this->getEntityManager();

        $em->persist($product);
        $em->flush();
    }
}
