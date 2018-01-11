<?php

declare(strict_types = 1);

namespace Gogart\Infrastructure\Projection;

use Doctrine\DBAL\Connection;
use Gogart\Application\Cart\Query\Exception\CartNotFoundException;
use Gogart\Application\Cart\Query\ReadRepository\CartReadRepositoryInterface;
use Gogart\Application\Cart\Query\ViewModel\CartView;
use Gogart\Application\Product\Query\Repository\ProductReadRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CartReadRepository implements CartReadRepositoryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var ProductReadRepositoryInterface
     */
    private $productReadRepository;

    /**
     * @param Connection $connection
     * @param ProductReadRepositoryInterface $productReadRepository
     */
    public function __construct(
        Connection $connection,
        ProductReadRepositoryInterface $productReadRepository
    ) {
        $this->connection = $connection;
        $this->productReadRepository = $productReadRepository;
    }

    /**
     * @param UuidInterface $cartId
     *
     * @return CartView
     *
     * @throws CartNotFoundException
     * @throws \InvalidArgumentException
     */
    public function get(UuidInterface $cartId): CartView
    {
        $this->assertCartExist($cartId);

        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('product_id')
            ->from(CartReadModel::TABLE)
            ->where('cart_id = :cart_id')
            ->setParameter('cart_id', $cartId->toString());

        $productIds = $qb->execute()->fetchAll(\PDO::FETCH_COLUMN);

        $hydratedProductIds = $this->hydrateProductIds($productIds);

        $productList = $this->productReadRepository->getByIds($hydratedProductIds);

        return new CartView($cartId->toString(), $productList, $productList->calculateTotalPrice());
    }

    /**
     * @param UuidInterface $cartId
     *
     * @throws CartNotFoundException
     */
    private function assertCartExist(UuidInterface $cartId): void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('cart_id')
            ->from(CartReadModel::TABLE)
            ->where('cart_id = :cart_id')
            ->setMaxResults(1)
            ->setParameter('cart_id', $cartId->toString());

        $cartExists = $qb->execute()->fetch();

        if ($cartExists === false) {
            throw new CartNotFoundException('Cart not found');
        }
    }

    /**
     * @param array $rawProductIds
     *
     * @return UuidInterface[]
     */
    private function hydrateProductIds(array $rawProductIds): array
    {
        return array_map(
            function (string $id) {
                return Uuid::fromString($id);
            },
            $rawProductIds
        );
    }
}
