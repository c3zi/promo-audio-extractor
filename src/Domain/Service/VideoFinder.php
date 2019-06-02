<?php

namespace Promo\VideoProcessor\Domain\Service;

use Promo\VideoProcessor\Domain\ValueObject\FoundVideo;

interface VideoFinder
{
    public function findOne(string $tag): FoundVideo;
}
