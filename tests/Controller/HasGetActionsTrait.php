<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;

trait HasGetActionsTrait
{
    /**
     * @dataProvider urlGetProvider
     */
    public function testGetPagesAreExist(string $url): void
    {
        $this->client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
    }
}