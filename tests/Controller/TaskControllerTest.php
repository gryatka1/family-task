<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\TaskGroup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends AbstractController
{
    const APP_ROUTE = self::BASE_APP_ROUTE . '/task';

    const TASK_GROUP_TITLE = 'unittest task group title';
    const TASK_TEXT = 'unittest task text';
    const NEW_TASK_GROUP_TITLE = 'unittest updated task group title';
    const NEW_TASK_TEXT = 'unittest updated task text';

    public function testCreateTask(): void
    {
        $taskGroup = (new TaskGroup(title: self::TASK_GROUP_TITLE));

        $this->saveEntities($taskGroup);

        $this->client->request(Request::METHOD_POST, self::APP_ROUTE . '/create', [
            'text' => self::TASK_TEXT,
            'taskGroupId' => $taskGroup->getId(),
        ]);

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertTaskDTO($responseContent);
        $this->isActiveTask($responseContent);

        $taskBD = $this->findTaskById($responseContent['id']);

        $this->deleteEntities($taskBD, $taskGroup);
    }

    public function testUpdateTaskText(): void
    {
        $taskGroup = (new TaskGroup(title: self::TASK_GROUP_TITLE));
        $task = (new Task(text: self::TASK_TEXT));
        $taskGroup->addTask($task);

        $this->saveEntities($task, $taskGroup);

        $this->client->request(Request::METHOD_POST, self::APP_ROUTE . '/update/text/' . $task->getId(), [
            'text' => self::NEW_TASK_TEXT,
        ]);

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertTaskDTO($responseContent);

        $this->assertEquals(self::NEW_TASK_TEXT, $responseContent['text']);
        $this->isActiveTask($responseContent);

        $this->deleteEntities($task, $taskGroup);
    }

    public function testUpdateTaskGroup(): void
    {
        $taskGroup = (new TaskGroup(title: self::TASK_GROUP_TITLE));
        $task = (new Task(text: self::TASK_TEXT));
        $taskGroup->addTask($task);

        $newTaskGroup = (new TaskGroup(title: self::NEW_TASK_GROUP_TITLE));

        $this->saveEntities($task, $taskGroup, $newTaskGroup);

        $this->client->request(Request::METHOD_POST, self::APP_ROUTE . '/update/group/' . $task->getId(), [
            'taskGroupId' => $newTaskGroup->getId(),
        ]);

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertTaskDTO($responseContent);

        $this->assertEquals(0, $taskGroup->getTasks()->count());
        $this->assertEquals($newTaskGroup->getId(), $responseContent['taskGroupId']);
        $this->isActiveTask($responseContent);

        $this->deleteEntities($task, $taskGroup, $newTaskGroup);
    }

    public function testDoneTask(): void
    {
        $taskGroup = (new TaskGroup(title: self::TASK_GROUP_TITLE));
        $task = (new Task(text: self::TASK_TEXT));
        $taskGroup->addTask($task);

        $this->saveEntities($task, $taskGroup);

        $this->client->request(Request::METHOD_POST, self::APP_ROUTE . '/done/' . $task->getId());

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertTaskDTO($responseContent);

        $this->assertNotNull($responseContent['doneAt']);
        $this->assertNull($responseContent['deletedAt']);

        $this->deleteEntities($task, $taskGroup);
    }

    public function testDeleteTask(): void
    {
        $taskGroup = (new TaskGroup(title: self::TASK_GROUP_TITLE));
        $task = (new Task(text: self::TASK_TEXT));
        $taskGroup->addTask($task);

        $this->saveEntities($task, $taskGroup);

        $this->client->request(Request::METHOD_DELETE, self::APP_ROUTE . '/delete/' . $task->getId());

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertTaskDTO($responseContent);

        $this->assertNotNull($responseContent['deletedAt']);

        $this->deleteEntities($task, $taskGroup);
    }

    protected function isActiveTask(array $responseContent): void
    {
        $this->assertNull($responseContent['doneAt']);
        $this->assertNull($responseContent['deletedAt']);
    }
}
