<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Infrastructure\Storage;

use Promo\VideoProcessor\Application\Storage\Storage;
use const DIRECTORY_SEPARATOR;
use function sprintf;

class Filesystem implements Storage
{
    /** @var string */
    private $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    public function exists(string $filename): bool
    {
        return file_exists($this->path($filename));
    }

    public function path(string $filename): string
    {
        return sprintf('%s%s%s', $this->basePath, DIRECTORY_SEPARATOR, $filename);
    }
}
