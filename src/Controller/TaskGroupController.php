<?php

namespace App\Controller;

use App\Entity\TaskGroup;
use App\Service\TaskGroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/v1/family-task/task-group', name: 'task-group_')]
class TaskGroupController extends AbstractController
{
    public function __construct(
        private readonly TaskGroupService $taskGroupService
    )
    {
    }

    #[Route('/create', name: 'create', methods: Request::METHOD_POST)]
    public function createTaskGroup(Request $request): JsonResponse
    {
        return $this->json($this->taskGroupService->createTaskGroup($request), Response::HTTP_CREATED);
    }

    #[Route('/group/{id}', name: 'get-task-group', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_GET)]
    public function getTaskGroup(TaskGroup $taskGroup): JsonResponse
    {
        return $this->json(TaskGroup::taskGroupAsDTO($taskGroup), Response::HTTP_OK);
    }

    #[Route('/catalog', name: 'get-task-catalog', methods: Request::METHOD_GET)]
    public function getCatalog(): JsonResponse
    {
        return $this->json($this->taskGroupService->getCatalog(), Response::HTTP_OK);
    }

    #[Route('/update-title/{id}', name: 'update-title', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_POST)]
    public function updateTaskGroupTitle(Request $request, TaskGroup $taskGroup): JsonResponse
    {
        return $this->json($this->taskGroupService->updateTaskGroupTitle($request, $taskGroup), Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_DELETE)]
    public function deleteTaskGroup(TaskGroup $taskGroup): JsonResponse
    {
        return $this->json($this->taskGroupService->deleteTaskGroup($taskGroup), Response::HTTP_OK);
    }
}
