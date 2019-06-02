<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Tests\Infrastructure;

use Promo\VideoProcessor\Domain\Exception\CannotFindVideoException;
use Promo\VideoProcessor\Domain\Exception\CannotParseUrlException;
use Promo\VideoProcessor\Infrastructure\Service\CrawlVideoFinder;
use Promo\VideoProcessor\Tests\TestCase;

final class CrawlVideoFinderTest extends TestCase
{
    public function testWhenCrawlForGamingTagOnPromoSite(): void
    {
        $url = 'http://promo.com/for';

        $crawler = new CrawlVideoFinder($url);
        $foundVideo = $crawler->findOne('gaming');

        $this->assertNotNull($foundVideo->url());
        $this->assertNotNull($foundVideo->id());
    }

    public function testWhenGivenUrlIsNotValid(): void
    {
        $this->expectException(CannotParseUrlException::class);
        $url = 'sdfsdfpromo.c';

        (new CrawlVideoFinder($url))->findOne('some-tag');
    }

    public function testWhenVideoCannotBeFound(): void
    {
        $this->expectException(CannotFindVideoException::class);
        $url = 'http://promo.com/for';

        (new CrawlVideoFinder($url, '.non-existent-tag-123-21-3'))->findOne('not-found-tag');
    }
}
