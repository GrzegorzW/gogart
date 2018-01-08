<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Data;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class ProductData
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
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(0)
     *
     * @Serializer\Type("int")
     */
    private $priceAmount;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Choice(choices={Gogart\Model\Shared\Currency::USD})
     *
     * @Serializer\Type("string")
     */
    private $priceCurrency;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getPriceAmount(): int
    {
        return $this->priceAmount;
    }

    /**
     * @return string
     */
    public function getPriceCurrency(): string
    {
        return $this->priceCurrency;
    }
}
