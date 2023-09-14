<?php

namespace App\Service;

use App\DTO\IdDTO;
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

    public function createTask(Request $request): IdDTO
    {
        $task = (new Task())
            ->setText($request->get('text'))
            ->setTaskGroup($this->taskGroupRepository->find($request->get('taskGroupId')));

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return new IdDTO(id: $task->getId());
    }

    public function updateTaskText(Task $task, Request $request): IdDTO
    {
        $task->setText($request->get('text'));

        $this->entityManager->flush();

        return new IdDTO(id: $task->getId());
    }

    public function updateTaskGroup(Task $task, Request $request): IdDTO
    {
        $task->setTaskGroup($this->taskGroupRepository->find($request->get('taskGroupId')));

        $this->entityManager->flush();

        return new IdDTO(id: $task->getId());
    }

    public function doneTask(Task $task): IdDTO
    {
        $task->setDoneAt(new DateTimeImmutable());

        $this->entityManager->flush();

        return new IdDTO(id: $task->getId());
    }

    public function removeTask(Task $task): IdDTO
    {
        $task->setDeletedAt(new DateTimeImmutable());

        $this->entityManager->flush();

        return new IdDTO(id: $task->getId());
    }
}
