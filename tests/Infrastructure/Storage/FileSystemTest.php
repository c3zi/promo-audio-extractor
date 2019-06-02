<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Tests\Infrastructure\Storage;

use PHPUnit\Framework\TestCase;
use Promo\VideoProcessor\Infrastructure\Storage\Filesystem;
use function uniqid;

final class FileSystemTest extends TestCase
{
    public function testPath(): void
    {
        $base = '/tmp';
        $storage = new Filesystem($base);

        $this->assertEquals('/tmp/file.mp4', $storage->path('file.mp4'));
    }

    public function testWhenFileDoesNotExists(): void
    {
        $base = '/tmp';
        $storage = new Filesystem($base);
        $file = uniqid('max-unique', true);

        $this->assertFalse($storage->exists($file));
    }
}
