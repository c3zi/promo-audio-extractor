<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Tests\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use Promo\VideoProcessor\Domain\Exception\DomainRuntimeException;
use Promo\VideoProcessor\Domain\ValueObject\FoundVideo;
use function uniqid;

final class FoundVideoTest extends TestCase
{
    public function testWhenUrlIsValid(): void
    {
        $id = uniqid('unique', true);
        $url = 'http://promo.com';
        $foundVideo = new FoundVideo($id, $url);

        $this->assertEquals($id, $foundVideo->id());
        $this->assertEquals($url, $foundVideo->url());
    }

    public function testWhenUrlIsNotValid(): void
    {
        $this->expectException(DomainRuntimeException::class);

        $id = uniqid('unique', true);
        $url = '/promo.c';

        new FoundVideo($id, $url);
    }
}
