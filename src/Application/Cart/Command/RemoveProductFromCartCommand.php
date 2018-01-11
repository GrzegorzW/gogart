<?php

declare(strict_types = 1);

namespace Gogart\Application\Cart\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Ramsey\Uuid\UuidInterface;

class RemoveProductFromCartCommand extends Command
{
    use PayloadTrait;

    /**
     * @return UuidInterface
     */
    public function getCartId(): UuidInterface
    {
        return $this->payload['cartId'];
    }

    /**
     * @return UuidInterface
     */
    public function getProductId(): UuidInterface
    {
        return $this->payload['productId'];
    }
}
