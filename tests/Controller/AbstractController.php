<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\TaskGroup;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractController extends WebTestCase
{
    const BASE_APP_ROUTE = '/api/v1/family-task';

    protected KernelBrowser $client;
    protected ?EntityManagerInterface $entityManager;
    protected ?EntityRepository $taskRepository;
    protected ?EntityRepository $taskGroupRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->taskRepository = $this->entityManager->getRepository(Task::class);
        $this->taskGroupRepository = $this->entityManager->getRepository(TaskGroup::class);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;
        $this->taskRepository = null;
        $this->taskGroupRepository = null;

        parent::tearDown();
    }

    protected function saveEntities(object ...$entities): void
    {
        foreach ($entities as $entity) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    protected function deleteEntities(object ...$entities): void
    {
        foreach ($entities as $entity) {
            $this->entityManager->remove($entity);
        }

        $this->entityManager->flush();
    }

    protected function findTaskById(string $id): ?Task
    {
        return $this->taskRepository->find($id);
    }

    protected function findTaskGroupById(string $id): ?TaskGroup
    {
        return $this->taskGroupRepository->find($id);
    }

    protected function assertTaskDTO(array $responseContent): void
    {
        $this->assertArrayHasKey('id', $responseContent);
        $this->assertArrayHasKey('text', $responseContent);
        $this->assertArrayHasKey('createdAt', $responseContent);
        $this->assertArrayHasKey('taskGroupId', $responseContent);
        $this->assertArrayHasKey('doneAt', $responseContent);
        $this->assertArrayHasKey('deletedAt', $responseContent);

        $this->assertIsInt($responseContent['id']);
        $this->assertIsString($responseContent['text']);
        $this->assertIsString($responseContent['createdAt']);
        $this->assertIsInt($responseContent['taskGroupId']);
    }

    protected function assertTaskGroupDTO(array $responseContent): void
    {
        $this->assertArrayHasKey('id', $responseContent);
        $this->assertArrayHasKey('title', $responseContent);
        $this->assertArrayHasKey('tasks', $responseContent);
        $this->assertArrayHasKey('createdAt', $responseContent);
        $this->assertArrayHasKey('deletedAt', $responseContent);

        $this->assertIsInt($responseContent['id']);
        $this->assertIsString($responseContent['title']);
        $this->assertIsString($responseContent['createdAt']);
        $this->assertIsArray($responseContent['tasks']);

        foreach ($responseContent['tasks'] as $task) {
            $this->assertTaskDTO($task);
        }
    }
}
