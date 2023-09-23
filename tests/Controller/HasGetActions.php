<?php

namespace App\Tests\Controller;

use Generator;

interface HasGetActions
{
    public function urlGetProvider(): Generator;

    /**
     * @dataProvider urlGetProvider
     */
    public function testGetPagesAreExist(string $url): void;
}