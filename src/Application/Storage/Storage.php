<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Application\Storage;

interface Storage
{
    public function exists(string $filename): bool;

    public function path(string $filename): string;
}
