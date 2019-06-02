<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Domain\ValueObject;

use Promo\VideoProcessor\Domain\Exception\DomainRuntimeException;
use const FILTER_VALIDATE_URL;
use function filter_var;

class FoundVideo
{
    /** @var string */
    private $id;
    /** @var string */
    private $url;

    public function __construct(string $id, string $url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new DomainRuntimeException(sprintf('Given url (%s) is not valid.', $url));
        }

        $this->id = $id;
        $this->url = $url;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function id(): string
    {
        return $this->id;
    }
}
