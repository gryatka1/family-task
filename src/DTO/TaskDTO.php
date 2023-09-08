<?php

namespace App\DTO;

use App\DTO\Traits\JsonSerializableTrait;
use DateTimeImmutable;
use JsonSerializable;

final class TaskDTO implements JsonSerializable
{
    use JsonSerializableTrait;

    public function __construct(
        private readonly int               $id,
        private readonly string            $text,
        private readonly DateTimeImmutable $createdAt,
        private readonly ?DateTimeImmutable $doneAt,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getDoneAt(): ?DateTimeImmutable
    {
        return $this->doneAt;
    }
}
