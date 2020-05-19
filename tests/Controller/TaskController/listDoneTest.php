<?php

namespace Tests\Controller\TaskController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListDoneTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function login($url): void
    {
        $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
    }
    public function urlSuccess()
    {
        yield ['/tasks/0/list'];
        yield ['/tasks/1/list'];
    }
    public function urlError()
    {
        yield ['/tasks/q/list'];
        yield ['/tasks/2/list'];
        yield ['/tasks/false/list'];
    }
    /**
     * @dataProvider urlSuccess
     */
    public function testSuccess($url)
    {
        $this->login($url);
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider urlError
     */
    public function testError($url)
    {
        $this->login($url);
        
        $this->assertNotEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
