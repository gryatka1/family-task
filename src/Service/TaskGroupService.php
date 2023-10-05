<?php

namespace App\Service;

use App\DTO\TaskDTO;
use App\DTO\TaskGroupDTO;
use App\Entity\TaskGroup;
use App\Repository\TaskGroupRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class TaskGroupService
{
    private EntityRepository|TaskGroupRepository $taskGroupRepository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
        $this->taskGroupRepository = $this->entityManager->getRepository(TaskGroup::class);
    }

    public function createTaskGroup(Request $request): TaskGroupDTO
    {
        $taskGroup = (new TaskGroup(title: $request->get('title')));

        $this->entityManager->persist($taskGroup);
        $this->entityManager->flush();

        return $this->getTaskGroupDTO($taskGroup);
    }

    /** @return ArrayCollection<TaskGroupDTO> */
    public function getAllTaskGroups(): ArrayCollection
    {
        $taskGroupsCollection = new ArrayCollection();

        $taskGroups = $this->taskGroupRepository->findAll();

        foreach ($taskGroups as $taskGroup) {
            $taskGroupDTO = $this->getTaskGroupDTO($taskGroup);
            $taskGroupsCollection->add($taskGroupDTO);
        }

        return $taskGroupsCollection;
    }

    public function getAllTaskGroupTitles(): array
    {
        $taskGroups = $this->taskGroupRepository->findAll();

        return array_reduce($taskGroups, function ($re, TaskGroup $taskGroup) {
            $re[$taskGroup->getId()] = $taskGroup->getTitle();
            return $re;
        }, []);
    }

    public function getTaskGroupDTO(TaskGroup $taskGroup): TaskGroupDTO
    {
        $taskDTOCollection = new ArrayCollection();

        foreach ($taskGroup->getTasks() as $task) {
            $taskDTO = new TaskDTO(
                id: $task->getId(),
                text: $task->getText(),
                createdAt: $task->getCreatedAt(),
                taskGroupId: $taskGroup->getId(),
                doneAt: $task->getDoneAt(),
                deletedAt: $task->getDeletedAt()
            );

            $taskDTOCollection->add($taskDTO);
        }

        return new TaskGroupDTO(
            id: $taskGroup->getId(),
            title: $taskGroup->getTitle(),
            tasks: $taskDTOCollection,
            createdAt: $taskGroup->getCreatedAt(),
            deletedAt: $taskGroup->getDeletedAt()
        );
    }

    public function updateTaskGroupTitle(Request $request, TaskGroup $taskGroup): TaskGroupDTO
    {
        $taskGroup->setTitle($request->get('title'));

        $this->entityManager->flush();

        return $this->getTaskGroupDTO($taskGroup);
    }

    public function removeTaskGroup(TaskGroup $taskGroup): TaskGroupDTO
    {
        $taskGroup->setDeletedAt(new DateTimeImmutable());

        foreach ($taskGroup->getTasks() as $task) {
            $task->setDeletedAt(new DateTimeImmutable());
        }

        $this->entityManager->flush();

        return $this->getTaskGroupDTO($taskGroup);
    }
}
