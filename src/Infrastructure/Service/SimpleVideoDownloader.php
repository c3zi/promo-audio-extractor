<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Infrastructure\Service;

use Promo\VideoProcessor\Application\Storage\Storage;
use Promo\VideoProcessor\Domain\Service\VideoDownloader;
use Promo\VideoProcessor\Domain\ValueObject\DownloadedVideo;
use Promo\VideoProcessor\Domain\ValueObject\FoundVideo;
use Promo\VideoProcessor\Domain\Exception\VideoCannotBeDownloadedException;
use function sprintf;

class SimpleVideoDownloader implements VideoDownloader
{
    /** @var string */
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function download(FoundVideo $video): DownloadedVideo
    {
        $path = $this->storage->path($video->id());

        if (!@copy($video->url(), $path)) {
            throw new VideoCannotBeDownloadedException(sprintf(
                'Given url (%s) cannot be saved to a path %s.',
                $video->url(),
                $path
            ));
        }

        return new DownloadedVideo($video->id(), $path);
    }
}
