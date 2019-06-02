<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Tests\Domain\Model;

use PHPUnit\Framework\TestCase;
use Promo\VideoProcessor\Domain\Exception\DomainRuntimeException;
use Promo\VideoProcessor\Domain\Model\Audio;
use function sys_get_temp_dir;
use function uniqid;

final class AudioTest extends TestCase
{
    public function testWhenTypeIsNotSupported(): void
    {
        $this->expectException(DomainRuntimeException::class);

        new Audio('1', 'a', 'n', 't');
    }

    public function testWhenAllParametersAreOk(): void
    {
        $id = uniqid('unique', true);
        $path = sys_get_temp_dir();
        $name = 'name';
        $type = 'mp3';

        $audio = new Audio($id, $path, $name, $type);

        $this->assertEquals($id, $audio->id());
        $this->assertEquals($path, $audio->path());
        $this->assertEquals($name, $audio->name());
        $this->assertEquals($type, $audio->type());
    }
}
