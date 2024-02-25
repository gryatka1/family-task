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
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class TaskGroupService
{
    private EntityRepository|TaskGroupRepository $taskGroupRepository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security
    )
    {
        $this->taskGroupRepository = $this->entityManager->getRepository(TaskGroup::class);
    }

    public function createTaskGroup(Request $request): TaskGroupDTO
    {
        $taskGroup = (new TaskGroup(
            title: $request->get('title'),
            createdByUserId: $this->security->getUser()->getId(),
        ));

        $this->save($taskGroup);

        return TaskGroup::taskGroupAsDTO($taskGroup);
    }

    public function getCatalog(): array
    {
        return [
            'groups' => $this->getAllTaskGroups(),
            'groupTitles' => $this->getAllTaskGroupTitles(),
        ];
    }

    /** @return ArrayCollection<TaskGroupDTO> */
    protected function getAllTaskGroups(): ArrayCollection
    {
        $taskGroupsCollection = new ArrayCollection();

        $taskGroups = $this->taskGroupRepository->findTaskGroupsByUser($this->security->getUser());

        foreach ($taskGroups as $taskGroup) {
            $taskGroupDTO = TaskGroup::taskGroupAsDTO($taskGroup);
            $taskGroupsCollection->add($taskGroupDTO);
        }

        return $taskGroupsCollection;
    }

    protected function getAllTaskGroupTitles(): array
    {
        return $this->taskGroupRepository->findTaskGroupsTitlesByUser($this->security->getUser());
    }

    public function updateTaskGroupTitle(Request $request, TaskGroup $taskGroup): TaskGroupDTO
    {
        $taskGroup->setTitle($request->get('title'));

        $this->save($taskGroup);

        return TaskGroup::taskGroupAsDTO($taskGroup);
    }

    public function deleteTaskGroup(TaskGroup $taskGroup): TaskGroupDTO
    {
        $taskGroup->setDeletedAt(new DateTimeImmutable());

        foreach ($taskGroup->getTasks() as $task) {
            $task->setDeletedAt(new DateTimeImmutable());
        }

        $this->save($taskGroup);

        return TaskGroup::taskGroupAsDTO($taskGroup);
    }

    private function save(TaskGroup $taskGroup): void
    {
        $this->entityManager->persist($taskGroup);
        $this->entityManager->flush();
    }
}
