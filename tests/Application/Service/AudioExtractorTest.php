<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Tests\Application\Service;

use PHPUnit\Framework\TestCase;
use Promo\VideoProcessor\Application\Dto\Tag;
use Promo\VideoProcessor\Application\Service\AudioExtractor;
use Promo\VideoProcessor\Domain\Service\AudioProcessor;
use Promo\VideoProcessor\Domain\Service\VideoDownloader;
use Promo\VideoProcessor\Domain\Service\VideoFinder;

final class AudioExtractorTest extends TestCase
{
    public function testPositiveScenario(): void
    {
        $videoFinder = $this->createMock(VideoFinder::class);
        $videoFinder
            ->expects($this->once())
            ->method('findOne');

        $videoDownloader = $this->createMock(VideoDownloader::class);
        $videoDownloader
            ->expects($this->once())
            ->method('download');

        $audioProcessor = $this->createMock(AudioProcessor::class);
        $audioProcessor
            ->expects(($this->once()))
            ->method('process');

        $extractor = new AudioExtractor($videoFinder, $videoDownloader, $audioProcessor);
        $extractor->extract(new Tag('gaming'));
    }
}
