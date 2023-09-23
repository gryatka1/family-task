<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\TaskGroup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskTest extends AbstractControllerTest
{
    const APP_ROUTE = self::BASE_APP_ROUTE . '/task';

    public function testCreate(): void
    {
        $taskGroupTitle = 'testCreateTask';
        $taskText = 'testCreateTask';

        $taskGroup = (new TaskGroup(title: $taskGroupTitle));

        $this->entityManager->persist($taskGroup);

        $this->entityManager->flush();

        $this->client->request(Request::METHOD_POST, self::APP_ROUTE . '/create', [
            'text' => $taskText,
            'taskGroupId' => $taskGroup->getId(),
        ]);

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertArrayHasKey('id', $responseContent);

        $entityId = $responseContent['id'];
        $task = $this->taskRepository->find($entityId);

        $this->assertEquals(Task::class, get_class($task));
        $this->assertEquals($taskText, $task->getText());
        $taskGroupBD = $task->getTaskGroup();
        $this->assertEquals($taskGroup->getId(), $taskGroupBD->getId());
        $this->assertEquals($taskGroupTitle, $taskGroupBD->getTitle());

        $this->entityManager->remove($task);
        $this->entityManager->remove($taskGroup);

        $this->entityManager->flush();
    }

    public function testUpdateText(): void
    {
        $taskGroupTitle = 'testUpdateTextTask';
        $taskText = 'testUpdateTextTask';
        $newTaskText = 'updated testUpdateTextTask';

        $taskGroup = (new TaskGroup(title: $taskGroupTitle));
        $task = (new Task(text: $taskText, taskGroup: $taskGroup));

        $this->entityManager->persist($taskGroup);
        $this->entityManager->persist($task);

        $this->entityManager->flush();

        $this->client->request(Request::METHOD_POST, self::APP_ROUTE . '/update/text/' . $task->getId(), [
            'text' => $newTaskText,
        ]);

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArrayHasKey('id', $responseContent);

        $entityId = $responseContent['id'];
        $task = $this->taskRepository->find($entityId);

        $this->assertEquals($newTaskText, $task->getText());

        $this->entityManager->remove($task);
        $this->entityManager->remove($taskGroup);

        $this->entityManager->flush();
    }

    public function testUpdateTaskGroup(): void
    {
        $taskGroupTitle = 'testUpdateTaskGroupTask';
        $taskText = 'testUpdateTaskGroupTask';
        $newTaskGroupTitle = 'updated testUpdateTaskGroupTask';

        $taskGroup = (new TaskGroup(title: $taskGroupTitle));
        $task = (new Task(text: $taskText, taskGroup: $taskGroup));

        $newTaskGroup = (new TaskGroup(title: $newTaskGroupTitle));

        $this->entityManager->persist($taskGroup);
        $this->entityManager->persist($task);
        $this->entityManager->persist($newTaskGroup);

        $this->entityManager->flush();

        $this->client->request(Request::METHOD_POST, self::APP_ROUTE . '/update/group/' . $task->getId(), [
            'taskGroupId' => $newTaskGroup->getId(),
        ]);

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArrayHasKey('id', $responseContent);

        $entityId = $responseContent['id'];
        $task = $this->taskRepository->find($entityId);

        $taskGroupBD = $task->getTaskGroup();
        $this->assertEquals($newTaskGroup->getId(), $taskGroupBD->getId());
        $this->assertEquals($newTaskGroupTitle, $taskGroupBD->getTitle());

        $this->entityManager->remove($task);
        $this->entityManager->remove($taskGroup);

        $this->entityManager->remove($newTaskGroup);

        $this->entityManager->flush();
    }

    public function testDone(): void
    {
        $taskGroupTitle = 'testDone';
        $taskText = 'testDone';

        $taskGroup = (new TaskGroup(title: $taskGroupTitle));
        $task = (new Task(text: $taskText, taskGroup: $taskGroup));

        $this->entityManager->persist($taskGroup);
        $this->entityManager->persist($task);

        $this->entityManager->flush();

        $this->client->request(Request::METHOD_POST, self::APP_ROUTE . '/done/' . $task->getId());

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArrayHasKey('id', $responseContent);

        $entityId = $responseContent['id'];
        $task = $this->taskRepository->find($entityId);

        $this->assertNotNull($task->getDoneAt());

        $this->entityManager->remove($task);
        $this->entityManager->remove($taskGroup);

        $this->entityManager->flush();
    }

    public function testDelete(): void
    {
        $taskGroupTitle = 'testDelete';
        $taskText = 'testDelete';

        $taskGroup = (new TaskGroup(title: $taskGroupTitle));
        $task = (new Task(text: $taskText, taskGroup: $taskGroup));

        $this->entityManager->persist($taskGroup);
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $this->client->request(Request::METHOD_DELETE, self::APP_ROUTE . '/delete/' . $task->getId());

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArrayHasKey('id', $responseContent);

        $entityId = $responseContent['id'];
        $task = $this->taskRepository->find($entityId);

        $this->assertNotNull($task->getDeletedAt());

        $this->entityManager->remove($task);
        $this->entityManager->remove($taskGroup);

        $this->entityManager->flush();
    }
}
