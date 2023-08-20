<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/family-task', name: 'app_task')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'task_index')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TaskController.php',
        ]);
    }

    #[Route('/test', name: 'task_index2')]
    public function index2(): JsonResponse
    {
        return $this->json([
            'message' => 'test2',
            'path' => 'src/Controller/TaskController.php',
        ]);
    }
}
