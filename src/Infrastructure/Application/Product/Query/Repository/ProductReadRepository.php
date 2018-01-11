<?php

declare(strict_types = 1);

namespace Gogart\Infrastructure\Application\Product\Query\Repository;

use Doctrine\DBAL\Connection;
use Gogart\Application\Product\Query\Repository\ProductReadRepositoryInterface;
use Gogart\Application\Product\Query\ViewModel\PaginatedProductListView;
use Gogart\Application\Product\Query\ViewModel\ProductListView;
use Gogart\Application\Product\Query\ViewModel\ProductView;
use Ramsey\Uuid\UuidInterface;

class ProductReadRepository implements ProductReadRepositoryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return PaginatedProductListView
     */
    public function list(int $page, int $perPage): PaginatedProductListView
    {
        $offset = ($page - 1) * $perPage;

        $totalCount = $this->getTotalCount();

        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('id, title, price_amount, price_currency')
            ->from('product')
            ->orderBy('title', 'ASC')
            ->andWhere('removed_at IS NULL')
            ->setFirstResult($offset)
            ->setMaxResults($perPage);

        $stmt = $qb->execute();

        $rows = $stmt->fetchAll();

        $products = $this->hydrateProducts($rows);

        return new PaginatedProductListView($products, $page, $perPage, $totalCount);
    }

    /**
     * @return int
     */
    private function getTotalCount(): int
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('COUNT(id)')
            ->from('product')
            ->andWhere('removed_at IS NULL');

        $stmt = $qb->execute();

        return (int)$stmt->fetchColumn();
    }

    /**
     * @param array $rawProducts
     *
     * @return ProductListView
     */
    private function hydrateProducts(array $rawProducts): ProductListView
    {
        $products = array_map(
            function ($productData) {
                return new ProductView(
                    $productData['id'],
                    $productData['title'],
                    (int)$productData['price_amount'],
                    $productData['price_currency']
                );
            },
            $rawProducts
        );

        return new ProductListView($products);
    }

    /**
     * @param UuidInterface[] $ids
     *
     * @return ProductListView
     */
    public function getByIds(array $ids): ProductListView
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('id, title, price_amount, price_currency')
            ->from('product')
            ->andWhere('id IN (:product_ids)')
            ->setParameter('product_ids', $ids, Connection::PARAM_STR_ARRAY);

        $stmt = $qb->execute();

        $rows = $stmt->fetchAll();

        return $this->hydrateProducts($rows);
    }
}
