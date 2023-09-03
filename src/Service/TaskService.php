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

    public function updateTaskText(Task $task, Request $request): void
    {
        $task->setText($request->get('text'));

        $this->entityManager->flush();
    }

    public function updateTaskGroup(Task $task, Request $request): void
    {
        $task->setTaskGroup($this->taskGroupRepository->find($request->get('taskGroupId')));

        $this->entityManager->flush();
    }

    public function doneTask(Task $task): void
    {
        $task->setDoneAt(new DateTimeImmutable());

        $this->entityManager->flush();
    }

    public function removeTask(Task $task): void
    {
        $task->setDeletedAt(new DateTimeImmutable());

        $this->entityManager->flush();
    }
}
