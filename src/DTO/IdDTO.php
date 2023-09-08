<?php

namespace App\DTO;

use App\DTO\Traits\JsonSerializableTrait;
use JsonSerializable;

final class IdDTO implements JsonSerializable
{
    use JsonSerializableTrait;

    public function __construct(
        private readonly int $id
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
