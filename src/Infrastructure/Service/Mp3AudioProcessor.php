<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Infrastructure\Service;

use Promo\VideoProcessor\Application\Storage\Storage;
use Promo\VideoProcessor\Domain\Model\Audio;
use Promo\VideoProcessor\Domain\Service\AudioProcessor;
use Promo\VideoProcessor\Domain\ValueObject\DownloadedVideo;
use Promo\VideoProcessor\Domain\Exception\AudioCannotBeExtractException;

class Mp3AudioProcessor implements AudioProcessor
{
    /** @var string */
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function process(DownloadedVideo $downloadedVideo): Audio
    {
        $filePath = $downloadedVideo->toString();
        $name = sprintf('%s.mp3', $downloadedVideo->id());
        $destination = $this->storage->path($name);
        $command = sprintf('ffmpeg -y -i %s -f mp3 -ab 192000 -vn %s 2> /dev/null', $filePath, $destination);

        exec($command, $output, $return);

        if ($return !== 0) {
            throw new AudioCannotBeExtractException(sprintf(
                'Audio cannot be extracted from %s and stored to %s',
                $filePath,
                $destination
            ));
        }

        return new Audio($downloadedVideo->id(), $destination, $name, 'mp3');
    }
}
