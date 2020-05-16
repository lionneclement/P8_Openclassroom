<?php

namespace Tests\Controller\TaskController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteTest extends WebTestCase
{
    public function url()
    {
        yield ['/tasks/3/delete'];
    }
    /**
     * @dataProvider url
     */
    public function testError($url)
    {
        $client = static::createClient();
        $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider url
     */
    public function testSuccess($url)
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request(
            'GET', '/login', [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $client->request('GET', '/task');
        $client->request('GET', $url);
        
        $this->assertEquals( 
            'http://localhost/task', 
            $client->getRequest()->getUri()
        );
    }
}
