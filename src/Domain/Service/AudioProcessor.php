<?php

namespace Promo\VideoProcessor\Domain\Service;

use Promo\VideoProcessor\Domain\Model\Audio;
use Promo\VideoProcessor\Domain\ValueObject\DownloadedVideo;

interface AudioProcessor
{
    public function process(DownloadedVideo $downloadedVideo): Audio;
}
