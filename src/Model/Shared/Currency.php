<?php

declare(strict_types = 1);

namespace Gogart\Model\Shared;

class Currency
{
    public const USD = 'USD';

    private const AVAILABLE_CURRENCIES = [
        self::USD
    ];

    /**
     * @var string
     */
    private $currency;

    /**
     * @param string $currency
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $currency)
    {
        if (\in_array($currency, self::AVAILABLE_CURRENCIES, true) === false) {
            throw new \InvalidArgumentException('Invalid currency');
        }

        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param Currency $other
     *
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->currency === $other->currency;
    }
}
