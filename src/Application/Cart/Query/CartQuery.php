<?php

declare(strict_types = 1);

namespace Gogart\Application\Cart\Query;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Ramsey\Uuid\UuidInterface;

class CartQuery extends Command
{
    use PayloadTrait;

    /**
     * @return UuidInterface
     */
    public function getCartId(): UuidInterface
    {
        return $this->payload['cartId'];
    }
}
