<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Query;

use Gogart\Application\Product\Data\PaginationData;

class ListProductQuery
{
    /**
     * @var PaginationData
     */
    private $data;

    /**
     * @param PaginationData $data
     */
    public function __construct(PaginationData $data)
    {
        $this->data = $data;
    }

    /**
     * @return PaginationData
     */
    public function getData(): PaginationData
    {
        return $this->data;
    }
}
