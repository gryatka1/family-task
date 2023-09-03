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

    #[Route('/create', name: 'create', methods: Request::METHOD_PUT)]
    public function createTaskGroup(Request $request): JsonResponse
    {
        return new JsonResponse($this->taskGroupService->createTaskGroup($request), Response::HTTP_CREATED);
    }

    #[Route('/group/{id}', name: 'get-task-group', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_GET)]
    public function getTaskGroup(TaskGroup $taskGroup): JsonResponse
    {
//        return $this->json($this->taskGroupService->getTaskGroup($taskGroup), Response::HTTP_OK);
        return new JsonResponse($this->taskGroupService->getTaskGroup($taskGroup), Response::HTTP_OK);
    }

    #[Route('/groups', name: 'get-task-groups', methods: Request::METHOD_GET)]
    public function getAllTaskGroups(): JsonResponse
    {
        return new JsonResponse($this->taskGroupService->getAllTaskGroups(), Response::HTTP_OK);
    }

    #[Route('/update-title/{id}', name: 'update-title', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_PUT)]
    public function updateTaskGroupTitle(Request $request, TaskGroup $taskGroup): JsonResponse
    {
        $this->taskGroupService->updateTaskGroupTitle($request, $taskGroup);

        return new JsonResponse([
            'message' => 'success'
        ], Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_DELETE)]
    public function deleteTask(TaskGroup $taskGroup): JsonResponse
    {
        $this->taskGroupService->removeTaskGroup($taskGroup);

        return new JsonResponse([
            'message' => 'success'
        ], Response::HTTP_OK);
    }
}