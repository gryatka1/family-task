<?php

namespace App\Service;

use App\DTO\TaskDTO;
use App\Entity\Task;
use App\Entity\TaskGroup;
use App\Repository\TaskGroupRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class TaskService
{
    private EntityRepository|TaskGroupRepository $taskGroupRepository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security
    )
    {
        $this->taskGroupRepository = $this->entityManager->getRepository(TaskGroup::class);
    }

    public function createTask(Request $request): TaskDTO
    {
        $taskGroup = $this->taskGroupRepository->find($request->get('taskGroupId'));
        $task = new Task(
            text: $request->get('text'),
            createdByUserId: $this->security->getUser()->getId(),
            assignedToUserId: $request->get('assignedToUserId'),
            taskGroup: $taskGroup,
        );
        $taskGroup->addTask($task);

        $this->save($task);

        return Task::taskAsDTO($task);
    }

    public function updateTask(Task $task, Request $request): TaskDTO
    {
        $task
            ->setText($request->get('text'))
            ->setAssignedToUserId($request->get('assignedToUserId'))
        ;

        $newTaskGroupId = $request->get('taskGroupId');
        if ($task->getTaskGroup()->getId() !== $newTaskGroupId) {
            $newTaskGroup = $this->taskGroupRepository->find($newTaskGroupId);
            $newTaskGroup->moveTaskToThisGroup($task);
        }

        $this->save($task);

        return Task::taskAsDTO($task);
    }

    public function doneTask(Task $task): TaskDTO
    {
        $task->setDoneAt(new DateTimeImmutable());

        $this->save($task);

        return Task::taskAsDTO($task);
    }

    public function deleteTask(Task $task): TaskDTO
    {
        $task->setDeletedAt(new DateTimeImmutable());

        $this->save($task);

        return Task::taskAsDTO($task);
    }

    private function save(Task $task): void
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}
