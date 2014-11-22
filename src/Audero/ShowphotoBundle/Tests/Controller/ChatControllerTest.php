<?php

namespace Audero\ShowphotoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChatControllerTest extends WebTestCase
{
    public function testPostmessage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/postMessage');
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/index');
    }

}
