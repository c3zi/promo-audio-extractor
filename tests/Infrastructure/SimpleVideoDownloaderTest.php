<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Tests\Infrastructure;

use Promo\VideoProcessor\Domain\ValueObject\FoundVideo;
use Promo\VideoProcessor\Domain\Exception\VideoCannotBeDownloadedException;
use Promo\VideoProcessor\Infrastructure\Service\SimpleVideoDownloader;
use Promo\VideoProcessor\Infrastructure\Storage\Filesystem;
use Promo\VideoProcessor\Tests\TestCase;
use function sys_get_temp_dir;
use function uniqid;
use function unlink;

final class SimpleVideoDownloaderTest extends TestCase
{
    public function testWhenGivenResourceExists(): void
    {
        $url = 'https://ak02-promo-cdn.slidely.com/promoVideos/videos/5b/5f/5b5f3ac976e0dfc9387b23c6/preview.mp4?dv=1';
        $id = uniqid('promo', true);

        $downloader = new SimpleVideoDownloader(new Filesystem(sys_get_temp_dir()));
        $downloadedVideo = $downloader->download(new FoundVideo($id, $url));

        $this->assertFileExists($downloadedVideo->toString());
        unlink($downloadedVideo->toString());
    }

    public function testWhenGivenUrlCannotBeSaved(): void
    {
        $this->expectException(VideoCannotBeDownloadedException::class);

        $url = 'https://url-does-not-exist.c';
        $id = uniqid('promo', true);

        $downloader = new SimpleVideoDownloader(new Filesystem(sys_get_temp_dir()));
        $downloader->download(new FoundVideo($id, $url));
    }
}
