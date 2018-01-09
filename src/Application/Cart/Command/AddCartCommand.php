<?php

declare(strict_types = 1);

namespace Gogart\Application\Cart\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Ramsey\Uuid\UuidInterface;

class AddCartCommand extends Command
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
