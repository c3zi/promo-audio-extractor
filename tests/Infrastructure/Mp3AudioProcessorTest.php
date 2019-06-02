<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Tests\Infrastructure;

use Promo\VideoProcessor\Domain\ValueObject\DownloadedVideo;
use Promo\VideoProcessor\Infrastructure\Service\Mp3AudioProcessor;
use Promo\VideoProcessor\Infrastructure\Storage\Filesystem;
use Promo\VideoProcessor\Tests\TestCase;
use function realpath;
use function sys_get_temp_dir;

final class Mp3AudioProcessorTest extends TestCase
{
    public function testExtractionAudioFromVideo(): void
    {
        $path = realpath(__DIR__ . '/../data/example.mp4');
        $downloaded = new DownloadedVideo('12345', $path);

        $processor = new Mp3AudioProcessor(new Filesystem(sys_get_temp_dir()));
        $audio = $processor->process($downloaded);

        $this->assertEquals('12345', $audio->id());
        $this->assertEquals('12345.mp3', $audio->name());
        $this->assertEquals(sys_get_temp_dir().'/12345.mp3', $audio->path());
        $this->assertEquals('mp3', $audio->type());
    }
}
