<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Command;

use Gogart\Application\Product\Data\PriceData;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Ramsey\Uuid\UuidInterface;

class ChangeProductPriceCommand extends Command
{
    use PayloadTrait;

    /**
     * @return PriceData
     */
    public function getTitleData(): PriceData
    {
        return $this->payload['data'];
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->payload['id'];
    }
}
