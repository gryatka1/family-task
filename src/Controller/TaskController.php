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

    #[Route('/update/{id}', name: 'update', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_POST)]
    public function updateTask(Task $task, Request $request): JsonResponse
    {
        return $this->json($this->taskService->updateTask($task, $request), Response::HTTP_OK);
    }

    #[Route('/done/{id}', name: 'done', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_POST)]
    public function doneTask(Task $task): JsonResponse
    {
        return $this->json($this->taskService->doneTask($task), Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: Request::METHOD_DELETE)]
    public function deleteTask(Task $task): JsonResponse
    {
        return $this->json($this->taskService->deleteTask($task), Response::HTTP_OK);
    }
}
