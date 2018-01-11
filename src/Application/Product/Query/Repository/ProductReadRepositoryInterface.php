<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Query\Repository;

use Gogart\Application\Product\Query\ViewModel\PaginatedProductListView;
use Gogart\Application\Product\Query\ViewModel\ProductListView;

interface ProductReadRepositoryInterface
{
    /**
     * @param int $page
     * @param int $perPage
     *
     * @return PaginatedProductListView
     */
    public function list(int $page, int $perPage): PaginatedProductListView;

    /**
     * @param array $ids
     *
     * @return ProductListView
     */
    public function getByIds(array $ids): ProductListView;
}
