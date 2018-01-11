<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Query\ViewModel;

class ProductView
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $price;

    /**
     * @var int
     */
    private $priceAmount;

    /**
     * @var string
     */
    private $priceCurrency;

    /**
     * @param string $id
     * @param string $title
     * @param int $priceAmount
     * @param string $priceCurrency
     */
    public function __construct(string $id, string $title, int $priceAmount, string $priceCurrency)
    {
        $this->id = $id;
        $this->title = $title;
        $this->price = sprintf('%0.2f %s', $priceAmount / 100, $priceCurrency);
        $this->priceAmount = $priceAmount;
        $this->priceCurrency = $priceCurrency;
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
