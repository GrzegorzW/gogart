<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Data;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class PaginationData
{
    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(1)
     *
     * @Serializer\Type("int")
     */
    private $page = 1;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\LessThanOrEqual(3)
     *
     * @Serializer\Type("int")
     */
    private $perPage = 3;

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
