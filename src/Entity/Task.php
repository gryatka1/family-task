<?php

namespace App\Entity;

use App\DTO\TaskDTO;
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

    #[ORM\Column(name: 'done_at', type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $doneAt = null;

    #[ORM\Column(name: 'created_by_user_id', type: 'integer')]
    private int $createdByUserId;

    #[ORM\Column(name: 'assigned_to_user_id', type: 'integer')]
    private int $assignedToUserId;

    #[ORM\ManyToOne(targetEntity: TaskGroup::class, cascade: ['persist', 'remove'], inversedBy: 'tasks')]
    #[ORM\JoinColumn(name: "task_group_id")]
    private TaskGroup $taskGroup;

    public function __construct(string $text, int $createdByUserId, int $assignedToUserId, TaskGroup $taskGroup)
    {
        $this->createdAt = new DateTimeImmutable();

        $this->text = $text;
        $this->taskGroup = $taskGroup;

        $this->createdByUserId = $createdByUserId;
        $this->assignedToUserId = $assignedToUserId;
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

    public function getCreatedByUserId(): int
    {
        return $this->createdByUserId;
    }

    public function getAssignedToUserId(): int
    {
        return $this->assignedToUserId;
    }

    public function setAssignedToUserId(int $assignedToUserId): static
    {
        $this->assignedToUserId = $assignedToUserId;

        return $this;
    }

    public static function taskAsDTO(Task $task): TaskDTO
    {
        return new TaskDTO(
            id: $task->getId(),
            text: $task->getText(),
            createdAt: $task->getCreatedAt(),
            taskGroupId: $task->getTaskGroup()->getId(),
            doneAt: $task->getDoneAt(),
            createdByUserId: $task->getCreatedByUserId(),
            assignedToUserId: $task->getAssignedToUserId(),
        );
    }
}
