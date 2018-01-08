<?php

declare(strict_types = 1);

namespace Gogart\Model\Cart\Event;

use Prooph\EventSourcing\AggregateChanged;

class ProductRemovedFromCart extends AggregateChanged
{
    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->payload['productId'];
    }
}
