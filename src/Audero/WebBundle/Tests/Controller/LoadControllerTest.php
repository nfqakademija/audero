<?php

namespace Audero\WebBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoadControllerTest extends WebTestCase
{
    public function testNewest()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/load/newest');
    }

}
