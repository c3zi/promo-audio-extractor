<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Application\Service;

use Promo\VideoProcessor\Application\Dto\Tag;
use Promo\VideoProcessor\Domain\Model\Audio;
use Promo\VideoProcessor\Domain\Service\AudioProcessor;
use Promo\VideoProcessor\Domain\Service\VideoDownloader;
use Promo\VideoProcessor\Domain\Service\VideoFinder;

class AudioExtractor
{
    /** @var VideoFinder */
    private $videoFinder;
    /** @var VideoDownloader */
    private $videoDownloader;
    /** @var AudioProcessor */
    private $audioProcessor;

    public function __construct(
        VideoFinder $videoFinder,
        VideoDownloader $videoDownloader,
        AudioProcessor $audioProcessor
    ) {
        $this->videoFinder = $videoFinder;
        $this->videoDownloader = $videoDownloader;
        $this->audioProcessor = $audioProcessor;
    }

    public function extract(Tag $tag): Audio
    {
        $foundVideo = $this->videoFinder->findOne($tag->tag());

        $downloadedVideo = $this->videoDownloader->download($foundVideo);

        return $this->audioProcessor->process($downloadedVideo);
    }
}
