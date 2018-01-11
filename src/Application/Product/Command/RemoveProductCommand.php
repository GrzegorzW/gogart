<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Ramsey\Uuid\UuidInterface;

class RemoveProductCommand extends Command
{
    use PayloadTrait;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->payload['id'];
    }
}
