<?php

declare(strict_types = 1);

namespace Gogart\Model\Product;

class Title
{
    /**
     * @var string
     */
    private $title;

    /**
     * @param string $title
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $title)
    {
        $trimmedTitle = trim($title);

        if ($trimmedTitle === '') {
            throw new \InvalidArgumentException('Title cannot be empty');
        }

        $this->title = $trimmedTitle;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param Title $other
     *
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->title === $other->title;
    }
}
