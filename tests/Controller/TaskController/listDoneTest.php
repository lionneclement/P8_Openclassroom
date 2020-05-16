<?php

namespace Tests\Controller\TaskController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListDoneTest extends WebTestCase
{
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
        $client = static::createClient();
        $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider urlError
     */
    public function testError($url)
    {
        $client = static::createClient();
        $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        
        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());
    }
}
