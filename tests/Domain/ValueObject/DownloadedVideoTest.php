<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Tests\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use Promo\VideoProcessor\Domain\ValueObject\DownloadedVideo;
use function uniqid;

final class DownloadedVideoTest extends TestCase
{
    public function testToStringAndId(): void
    {
        $id = uniqid('unique', true);
        $downloaded = new DownloadedVideo($id, '/tmp/');

        $this->assertEquals($id, $downloaded->id());
        $this->assertEquals('/tmp/', $downloaded->toString());
    }
}
