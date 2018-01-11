<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Query\Handler;

use Gogart\Application\Product\Query\ListProductQuery;
use Gogart\Application\Product\Query\Repository\ProductReadRepositoryInterface;
use React\Promise\Deferred;

class ListProductHandler
{
    /**
     * @var ProductReadRepositoryInterface
     */
    private $productReadRepository;

    /**
     * @param ProductReadRepositoryInterface $productReadRepository
     */
    public function __construct(ProductReadRepositoryInterface $productReadRepository)
    {
        $this->productReadRepository = $productReadRepository;
    }

    /**
     * @param ListProductQuery $query
     * @param Deferred $deferred
     */
    public function __invoke(ListProductQuery $query, Deferred $deferred)
    {
        $queryData = $query->getData();

        $list = $this->productReadRepository->list($queryData->getPage(), $queryData->getPerPage());

        $deferred->resolve($list);
    }
}
