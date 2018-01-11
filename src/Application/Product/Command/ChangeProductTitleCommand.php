<?php

declare(strict_types = 1);

namespace Gogart\Application\Product\Command;

use Gogart\Application\Product\Data\TitleData;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Ramsey\Uuid\UuidInterface;

class ChangeProductTitleCommand extends Command
{
    use PayloadTrait;

    /**
     * @return TitleData
     */
    public function getTitleData(): TitleData
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
