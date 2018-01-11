<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Command;

use Gogart\Application\Product\Data\ProductData;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Ramsey\Uuid\UuidInterface;

class AddProductCommand extends Command
{
    use PayloadTrait;

    /**
     * @return ProductData
     */
    public function getProductData(): ProductData
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
