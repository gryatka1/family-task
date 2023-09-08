<?php

namespace App\Controller;

use App\Entity\Task;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/v1/family-task/task', name: 'task_')]
class TaskController extends AbstractController
{
    public function __construct(
        private readonly TaskService $taskService
    )
    {
    }

    #[Route('/create', name: 'create', methods: Request::METHOD_POST)]
    public function createTask(Request $request): JsonResponse
    {
        return $this->json($this->taskService->createTask($request), Response::HTTP_CREATED);
    }

    #[Route('/update/text/{id}', name: 'update-text', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_POST)]
    public function updateTaskText(Task $task, Request $request): JsonResponse
    {
        $this->taskService->updateTaskText($task, $request);
        return $this->json([
            'message' => 'success'
        ], Response::HTTP_OK);
    }

    #[Route('/update/group/{id}', name: 'update-group', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_POST)]
    public function updateTaskGroup(Task $task, Request $request): JsonResponse
    {
        $this->taskService->updateTaskGroup($task, $request);
        return $this->json([
            'message' => 'success'
        ], Response::HTTP_OK);
    }

    #[Route('/done/{id}', name: 'done', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_POST)]
    public function doneTask(Task $task): JsonResponse
    {
        $this->taskService->doneTask($task);
        return $this->json([
            'message' => 'success'
        ], Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_DELETE)]
    public function deleteTask(Task $task): JsonResponse
    {
        $this->taskService->removeTask($task);
        return $this->json([
            'message' => 'success'
        ], Response::HTTP_OK);
    }
}
