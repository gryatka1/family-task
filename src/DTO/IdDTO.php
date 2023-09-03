<?php

namespace App\DTO;

final class IdDTO
{
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
