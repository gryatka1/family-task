<?php

namespace App\Service;

use App\DTO\IdDTO;
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

    public function createTaskGroup(Request $request): IdDTO
    {
        $taskGroup = (new TaskGroup())
            ->setTitle($request->get('title'));

        $this->entityManager->persist($taskGroup);
        $this->entityManager->flush();

        return new IdDTO(id: $taskGroup->getId());
    }

    public function getAllTaskGroups(): ArrayCollection
    {
        $taskGroupsCollection = new ArrayCollection();

        $taskGroups = $this->taskGroupRepository->findAll();

        foreach ($taskGroups as $taskGroup) {
            $taskGroupDTO = $this->getTaskGroup($taskGroup);
            $taskGroupsCollection->add($taskGroupDTO);
        }

        return $taskGroupsCollection;
    }

    public function getTaskGroup(TaskGroup $taskGroup): TaskGroupDTO
    {
        $taskDTOCollection = new ArrayCollection();

        foreach ($taskGroup->getTasks() as $task) {
            $taskDTO = new TaskDTO(
                id: $task->getId(),
                text: $task->getText(),
                createdAt: $task->getCreatedAt(),
                doneAt: $task->getDoneAt()
            );

            $taskDTOCollection->add($taskDTO);
        }

        return new TaskGroupDTO(
            id: $taskGroup->getId(),
            title: $taskGroup->getTitle(),
            tasks: $taskDTOCollection
        );
    }

    public function updateTaskGroupTitle(Request $request, TaskGroup $taskGroup): IdDTO
    {
        $taskGroup->setTitle($request->get('title'));

        $this->entityManager->flush();

        return new IdDTO(id: $taskGroup->getId());
    }

    public function removeTaskGroup(TaskGroup $taskGroup): IdDTO
    {
        $taskGroup->setDeletedAt(new DateTimeImmutable());

        $this->entityManager->flush();

        return new IdDTO(id: $taskGroup->getId());
    }
}
