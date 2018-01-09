<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Query\ViewModel;

class ProductListView
{
    /**
     * @var array
     */
    private $products;

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
     * @param array $products
     * @param int $page
     * @param int $perPage
     * @param int $totalCount
     */
    public function __construct(array $products, int $page, int $perPage, int $totalCount)
    {
        $this->products = $products;
        $this->page = $page;
        $this->perPage = $perPage;
        $this->totalCount = $totalCount;
    }
}
