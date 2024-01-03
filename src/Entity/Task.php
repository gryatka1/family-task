<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name: '`task`')]
#[ORM\Index(columns: ['id'], name: 'task_id_idx')]
class Task
{
    use Trait\SoftDelete;
    use Trait\CreatedAt;

    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $text;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $doneAt = null;

    #[ORM\ManyToOne(targetEntity: TaskGroup::class, cascade: ['persist', 'remove'], inversedBy: 'tasks')]
    #[ORM\Column(name: '`task_group_id`', nullable: false)]
    private ?TaskGroup $taskGroup = null;

    public function __construct(string $text)
    {
        $this->createdAt = new DateTimeImmutable();

        $this->text = $text;
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

    public function getTaskGroup(): ?TaskGroup
    {
        return $this->taskGroup;
    }

    public function setTaskGroup(TaskGroup $taskGroup): static
    {
        $this->taskGroup = $taskGroup;

        return $this;
    }
}
