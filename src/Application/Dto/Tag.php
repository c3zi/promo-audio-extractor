<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Application\Dto;

use Promo\VideoProcessor\Application\Exception\UnsupportedTagException;
use function sprintf;
use function urldecode;
use function in_array;

class Tag
{
    private const ALLOWED_TAGS = [
        'travel',
        'gaming',
        'ecommerce',
        'marketing-videos',
        'facebook-ads',
    ];

    /** @var string */
    private $tag;

    public function __construct(string $tag)
    {
        $tag = urldecode($tag);

        if (!in_array($tag, self::ALLOWED_TAGS, true)) {
            throw new UnsupportedTagException(sprintf('Given tag (%s) is not supported.', $tag));
        }

        $this->tag = $tag;
    }

    public function tag(): string
    {
        return $this->tag;
    }
}
