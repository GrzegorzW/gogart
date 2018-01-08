<?php

declare(strict_types = 1);

namespace Gogart\Model\Shared;

class Amount
{
    /**
     * @var int
     */
    private $amount;

    /**
     * @param int $amount
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $amount)
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative');
        }

        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param Amount $other
     *
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->amount === $other->amount;
    }
}
