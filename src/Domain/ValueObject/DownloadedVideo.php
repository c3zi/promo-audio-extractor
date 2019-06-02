<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Domain\ValueObject;

class DownloadedVideo
{
    /** @var string */
    private $path;
    /** @var string */
    private $id;

    public function __construct(string $id, string $path)
    {
        $this->path = $path;
        $this->id = $id;
    }

    public function toString(): string
    {
        return $this->path;
    }

    public function id(): string
    {
        return $this->id;
    }
}
