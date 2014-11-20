<?php

namespace Audero\WebBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/profile');
    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/profile');
    }

}
