<?php

namespace App\DTO;

use DateTimeImmutable;

final class TaskDTO
{
    public function __construct(
        private readonly int               $id,
        private readonly string            $text,
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $doneAt,
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

    public function getDoneAt(): DateTimeImmutable
    {
        return $this->doneAt;
    }
}
