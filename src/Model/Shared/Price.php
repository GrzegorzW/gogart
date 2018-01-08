<?php

declare(strict_types = 1);

namespace Gogart\Model\Shared;

class Price
{
    /**
     * @var Amount
     */
    private $amount;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @param Amount $amount
     * @param Currency $currency
     */
    public function __construct(Amount $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @param string $price
     *
     * @return Price
     *
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $price): Price
    {
        $amount = new Amount((int)substr($price, 0, strpos($price, ' ')));
        $currency = new Currency(substr($price, strpos($price, ' ') + 1));

        return new self($amount, $currency);
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return sprintf('%d %s', $this->amount->getAmount(), $this->currency->getCurrency());
    }

    /**
     * @param Price $other
     *
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->amount->equals($other->amount) && $this->currency->equals($other->currency);
    }
}
