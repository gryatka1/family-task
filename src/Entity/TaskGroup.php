<?php

namespace App\Entity;

use App\DTO\TaskGroupDTO;
use App\Entity\Trait;
use App\Repository\TaskGroupRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskGroupRepository::class)]
#[ORM\Table(name: '`task_group`')]
#[ORM\Index(columns: ['id'], name: 'task_group_id_idx')]
class TaskGroup
{
    use Trait\SoftDelete;
    use Trait\CreatedAt;

    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\OneToMany(mappedBy: 'taskGroup', targetEntity: Task::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $tasks;

    #[ORM\Column(name: 'created_by_user_id', type: 'integer')]
    private int $createdByUserId;

    public function __construct($title, int $createdByUserId)
    {
        $this->createdAt = new DateTimeImmutable();
        $this->tasks = new ArrayCollection();

        $this->title = $title;

        $this->createdByUserId = $createdByUserId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks->filter(fn (Task $task) => !$task->isDeleted());
    }

    public function addTask(Task $task): static
    {
        $this->tasks->add($task);

        return $this;
    }

    public function moveTaskToThisGroup(Task $task): static
    {
        $task->getTaskGroup()->getTasks()->removeElement($task);

        $this->tasks->add($task);
        $task->setTaskGroup($this);

        return $this;
    }

    public function getCreatedByUserId(): int
    {
        return $this->createdByUserId;
    }

    public static function taskGroupAsDTO(TaskGroup $taskGroup): TaskGroupDTO
    {
        $taskDTOCollection = new ArrayCollection();

        foreach ($taskGroup->getTasks() as $task) {
            $taskDTOCollection->add(Task::taskAsDTO($task));
        }

        return new TaskGroupDTO(
            id: $taskGroup->getId(),
            title: $taskGroup->getTitle(),
            tasks: $taskDTOCollection,
            createdAt: $taskGroup->getCreatedAt(),
            deletedAt: $taskGroup->getDeletedAt(),
            createdByUserId: $taskGroup->getCreatedByUserId(),
        );
    }
}
