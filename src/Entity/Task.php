<?php

namespace App\Entity;

use App\Entity\Trait;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    use Trait\SoftDelete;
    use Trait\CreatedAt;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $text;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $doneAt = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private TaskGroup $taskGroup;

    public function __construct(string $text, TaskGroup $taskGroup)
    {
        $this->createdAt = new DateTimeImmutable();

        $this->text = $text;
        $this->taskGroup = $taskGroup;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getDoneAt(): ?DateTimeImmutable
    {
        return $this->doneAt;
    }

    public function setDoneAt(DateTimeImmutable $doneAt): static
    {
        $this->doneAt = $doneAt;

        return $this;
    }

    public function getTaskGroup(): TaskGroup
    {
        return $this->taskGroup;
    }

    public function setTaskGroup(TaskGroup $taskGroup): static
    {
        $this->taskGroup = $taskGroup;

        return $this;
    }
}
