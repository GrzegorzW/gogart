<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Data;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class TitleData
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Type("string")
     */
    private $title;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
