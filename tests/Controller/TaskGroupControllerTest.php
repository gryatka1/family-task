<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\TaskGroup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskGroupControllerTest extends AbstractController
{
    const APP_ROUTE = self::BASE_APP_ROUTE . '/task-group';

    const TASK_GROUP_TITLE = 'unittest task group title';
    const TASK_TEXT = 'unittest task text';
    const NEW_TASK_GROUP_TITLE = 'unittest updated task group title';
    const NEW_TASK_TEXT = 'unittest updated task text';

    public function testCreateTaskGroup(): void
    {
        $this->client->request(Request::METHOD_POST, self::APP_ROUTE . '/create', [
            'title' => self::TASK_GROUP_TITLE,
        ]);

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertTaskGroupDTO($responseContent);
        $this->assertNull($responseContent['deletedAt']);

        $taskGroupBD = $this->findTaskGroupById($responseContent['id']);

        $this->deleteEntities($taskGroupBD);
    }

    public function testGetTaskGroup(): void
    {
        $taskGroup = (new TaskGroup(title: self::TASK_GROUP_TITLE));
        $task1 = (new Task(text: self::TASK_TEXT));
        $taskGroup->addTask($task1);
        $task2 = (new Task(text: self::NEW_TASK_TEXT));
        $taskGroup->addTask($task2);

        $this->saveEntities($task1, $task2, $taskGroup);

        $this->client->request(Request::METHOD_GET, self::APP_ROUTE . '/group/' . $taskGroup->getId());

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertTaskGroupDTO($responseContent);
        $this->assertNull($responseContent['deletedAt']);

        $this->deleteEntities($task1, $task2, $taskGroup);
    }

    public function testGetAllTaskGroups(): void
    {
        $taskGroup = (new TaskGroup(title: self::TASK_GROUP_TITLE));
        $task = (new Task(text: self::TASK_TEXT));
        $taskGroup->addTask($task);

        $newTaskGroup = (new TaskGroup(title: self::NEW_TASK_GROUP_TITLE));
        $newTask = (new Task(text: self::NEW_TASK_TEXT));
        $newTaskGroup->addTask($newTask);

        $this->saveEntities($task, $newTask, $taskGroup, $newTaskGroup);

        $this->client->request(Request::METHOD_GET, self::APP_ROUTE . '/groups');

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertIsArray($responseContent);
        foreach ($responseContent as $taskGroupDTO) {
            $this->assertTaskGroupDTO($taskGroupDTO);
        }

        $this->deleteEntities($task, $newTask, $taskGroup, $newTaskGroup);
    }

    public function testGetAllTaskGroupTitles(): void
    {
        $taskGroup = (new TaskGroup(title: self::TASK_GROUP_TITLE));
        $task = (new Task(text: self::TASK_TEXT));
        $taskGroup->addTask($task);

        $newTaskGroup = (new TaskGroup(title: self::NEW_TASK_GROUP_TITLE));
        $newTask = (new Task(text: self::NEW_TASK_TEXT));
        $newTaskGroup->addTask($newTask);

        $this->saveEntities($task, $newTask, $taskGroup, $newTaskGroup);

        $this->client->request(Request::METHOD_GET, self::APP_ROUTE . '/group-titles');

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertIsArray($responseContent);

        $this->deleteEntities($task, $newTask, $taskGroup, $newTaskGroup);
    }

    public function testUpdateTaskGroupTitle(): void
    {
        $taskGroup = (new TaskGroup(title: self::TASK_GROUP_TITLE));

        $this->saveEntities($taskGroup);

        $this->client->request(Request::METHOD_POST, self::APP_ROUTE . '/update-title/' . $taskGroup->getId(), [
            'title' => self::NEW_TASK_GROUP_TITLE,
        ]);

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertTaskGroupDTO($responseContent);
        $this->assertNull($responseContent['deletedAt']);
        $this->assertEquals(self::NEW_TASK_GROUP_TITLE, $responseContent['title']);

        $this->deleteEntities($taskGroup);
    }

    public function testDeleteTaskGroup(): void
    {
        $taskGroup = (new TaskGroup(title: self::TASK_GROUP_TITLE));

        $this->saveEntities($taskGroup);

        $this->client->request(Request::METHOD_DELETE, self::APP_ROUTE . '/delete/' . $taskGroup->getId());

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertTaskGroupDTO($responseContent);
        $this->assertNotNull($responseContent['deletedAt']);

        $this->deleteEntities($taskGroup);
    }
}
