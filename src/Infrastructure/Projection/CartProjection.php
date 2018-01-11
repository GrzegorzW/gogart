<?php

declare(strict_types = 1);

namespace Gogart\Infrastructure\Projection;

use Gogart\Model\Cart\Event\ProductAddedToCart;
use Gogart\Model\Cart\Event\ProductRemovedFromCart;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;

class CartProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->when([
                ProductAddedToCart::class => function ($state, ProductAddedToCart $event) {
                    /** @var CartReadModel $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack(
                        'insert',
                        [
                            'product_id' => $event->getProductId(),
                            'cart_id' => $event->aggregateId(),
                        ]
                    );
                },
                ProductRemovedFromCart::class => function ($state, ProductRemovedFromCart $event) {
                    /** @var CartReadModel $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack(
                        'deleteProduct',
                        [
                            'product_id' => $event->getProductId(),
                            'cart_id' => $event->aggregateId(),
                        ]
                    );
                },
            ]);

        return $projector;
    }
}
