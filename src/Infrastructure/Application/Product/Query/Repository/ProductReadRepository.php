<?php

declare(strict_types = 1);

namespace Gogart\Infrastructure\Application\Product\Query\Repository;

use Doctrine\DBAL\Connection;
use Gogart\Application\Product\Query\Repository\ProductReadRepositoryInterface;
use Gogart\Application\Product\Query\ViewModel\ProductListView;
use Gogart\Application\Product\Query\ViewModel\ProductView;

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
     * @return ProductListView
     */
    public function list(int $page, int $perPage): ProductListView
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

        return $this->hydrate($rows, $page, $perPage, $totalCount);
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
     * @param int $page
     * @param int $perPage
     * @param int $totalCount
     *
     * @return ProductListView
     */
    private function hydrate(array $rawProducts, int $page, int $perPage, int $totalCount): ProductListView
    {
        $list = array_map(
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

        return new ProductListView($list, $page, $perPage, $totalCount);
    }
}
