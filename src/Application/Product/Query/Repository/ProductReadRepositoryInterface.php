<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Query\Repository;

use Gogart\Application\Product\Query\ViewModel\ProductListView;

interface ProductReadRepositoryInterface
{
    /**
     * @param int $page
     * @param int $perPage
     *
     * @return ProductListView
     */
    public function list(int $page, int $perPage): ProductListView;
}
