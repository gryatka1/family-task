<?php

namespace App\DTO;

use App\DTO\Traits\JsonSerializableTrait;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

final class TaskGroupDTO implements JsonSerializable
{
    use JsonSerializableTrait;

    public function __construct(
        private readonly int        $id,
        private readonly string     $title,
        private readonly Collection $tasks,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /** @return Collection<TaskDTO> */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }
}
