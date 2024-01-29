<?php

namespace App\Service;

use App\DTO\TaskDTO;
use App\Entity\Task;
use App\Entity\TaskGroup;
use App\Repository\TaskGroupRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class TaskService
{
    private EntityRepository|TaskGroupRepository $taskGroupRepository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
        $this->taskGroupRepository = $this->entityManager->getRepository(TaskGroup::class);
    }

    public function createTask(Request $request): TaskDTO
    {
        $taskGroup = $this->taskGroupRepository->find($request->get('taskGroupId'));
        $task = (new Task(text: $request->get('text')));
        $taskGroup->addTask($task);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $this->getTaskDTO($task, $taskGroup);
    }

    public function updateTask(Task $task, Request $request): TaskDTO
    {
        $task->setText($request->get('text'));
        $taskGroup = $this->taskGroupRepository->find($request->get('taskGroupId'));
        $task->setTaskGroup($taskGroup);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $this->getTaskDTO($task);
    }

    public function doneTask(Task $task): TaskDTO
    {
        $task->setDoneAt(new DateTimeImmutable());

        $this->entityManager->flush();

        return $this->getTaskDTO($task);
    }

    public function removeTask(Task $task): TaskDTO
    {
        $task->setDeletedAt(new DateTimeImmutable());

        $this->entityManager->flush();

        return $this->getTaskDTO($task);
    }

    protected function getTaskDTO(Task $task, TaskGroup $taskGroup = null): TaskDTO
    {
        return new TaskDTO(
            id: $task->getId(),
            text: $task->getText(),
            createdAt: $task->getCreatedAt(),
            taskGroupId: $taskGroup ? $taskGroup->getId() : $task->getTaskGroup()->getId(),
            doneAt: $task->getDoneAt(),
        );
    }
}
