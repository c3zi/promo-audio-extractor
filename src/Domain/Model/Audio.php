<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Domain\Model;

use Promo\VideoProcessor\Domain\Exception\DomainRuntimeException;
use function in_array;

class Audio
{
    private const SUPPORTED_TYPES = [
        'mp3',
    ];

    /** @var string */
    private $id;
    /** @var string */
    private $path;
    /** @var string */
    private $type;
    /** @var string */
    private $name;

    public function __construct(string $id, string $path, string $name, string $type)
    {
        if (!in_array($type, self::SUPPORTED_TYPES, true)) {
            throw new DomainRuntimeException(sprintf('Given type (%s) is not supported.', $type));
        }

        $this->id = $id;
        $this->path = $path;
        $this->type = $type;
        $this->name = $name;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function name(): string
    {
        return $this->name;
    }
}
