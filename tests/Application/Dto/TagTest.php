<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Tests\Application\Dto;

use PHPUnit\Framework\TestCase;
use Promo\VideoProcessor\Application\Dto\Tag;

final class TagTest extends TestCase
{

    /**
     * @dataProvider provideTags
     * @param string $input
     */
    public function testWhenTagIsSupported(string $input): void
    {
        $tagDto = new Tag($input);

        $this->assertEquals($input, $tagDto->tag());
    }

    public function provideTags(): array
    {
        return [
            ['travel'],
            ['gaming'],
            ['ecommerce'],
            ['marketing-videos'],
            ['facebook-ads'],
        ];
    }
}
