<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Query\ViewModel;

class PaginatedProductListView
{
    /**
     * @var ProductListView
     */
    private $data;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $perPage;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * @param ProductListView
     * @param int $page
     * @param int $perPage
     * @param int $totalCount
     */
    public function __construct(ProductListView $products, int $page, int $perPage, int $totalCount)
    {
        $this->data = $products;
        $this->page = $page;
        $this->perPage = $perPage;
        $this->totalCount = $totalCount;
    }
}
