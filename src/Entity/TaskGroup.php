<?php

namespace App\Entity;

use App\Entity\Trait;
use App\Repository\TaskGroupRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskGroupRepository::class)]
class TaskGroup
{
    use Trait\SoftDelete;
    use Trait\CreatedAt;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\OneToMany(mappedBy: 'taskGroup', targetEntity: Task::class)]
    private Collection $tasks;

    public function __construct($title)
    {
        $this->createdAt = new DateTimeImmutable();
        $this->tasks = new ArrayCollection();

        $this->title = $title;
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
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setTaskGroup($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            if ($task->getTaskGroup() === $this) {
                $task->setTaskGroup(null);
            }
        }

        return $this;
    }
}
