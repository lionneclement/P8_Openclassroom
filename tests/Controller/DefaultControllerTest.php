<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        
        $client->request(
            'GET', "/", [], [], [
            'PHP_AUTH_USER' => 'lionneclement@gmail.com',
            'PHP_AUTH_PW'   => 'password',
            ]
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
