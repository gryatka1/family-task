<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractControllerTest extends WebTestCase
{
    const BASE_APP_ROUTE = '/api/v1/family-task';

    protected KernelBrowser $client;
    protected ?EntityManagerInterface $entityManager;
    protected ?EntityRepository $taskRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->taskRepository = $this->entityManager->getRepository(Task::class);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;
        $this->taskRepository = null;

        parent::tearDown();
    }
}