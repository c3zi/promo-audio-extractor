<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Infrastructure\Service;

use Promo\VideoProcessor\Domain\Service\VideoFinder;
use Promo\VideoProcessor\Domain\ValueObject\FoundVideo;
use Promo\VideoProcessor\Domain\Exception\CannotFindVideoException;
use Promo\VideoProcessor\Domain\Exception\CannotParseUrlException;
use Symfony\Component\DomCrawler\Crawler;
use function sprintf;
use function file_get_contents;

final class CrawlVideoFinder implements VideoFinder
{
    private const PATTERN = '.row > .exp-video';

    /** @var string */
    private $url;
    /** @var string */
    private $pattern;

    public function __construct(string $url, string $pattern = self::PATTERN)
    {
        $this->url = $url;
        $this->pattern = $pattern;
    }

    public function findOne(string $tag): FoundVideo
    {
        $content = @file_get_contents(sprintf('%s/%s', $this->url, $tag));

        if (!$content) {
            throw new CannotParseUrlException(sprintf('Given url (%s) could not be parsed.', $this->url));
        }

        $crawler = new Crawler($content);

        $foundElements = $crawler->filter($this->pattern);

        if ($foundElements->count() === 0) {
            throw new CannotFindVideoException(sprintf(
                'Crawler could not find a video for given url (%s)',
                $this->url
            ));
        }

        $element = $foundElements->first();

        $id = $element->attr('data-id');
        $url = $element->attr('data-preview-url');

        if ($id === null || $url === null) {
            throw new CannotFindVideoException(sprintf(
                'Crawler could not find `id` or `url` attribute for a given url (%s)',
                $this->url
            ));
        }

        return new FoundVideo($id, $url);
    }
}
