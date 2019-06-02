<?php

namespace Promo\VideoProcessor\Domain\Service;

use Promo\VideoProcessor\Domain\ValueObject\DownloadedVideo;
use Promo\VideoProcessor\Domain\ValueObject\FoundVideo;

interface VideoDownloader
{
    public function download(FoundVideo $video): DownloadedVideo;
}
