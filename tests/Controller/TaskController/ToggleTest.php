<?php

namespace Tests\Controller\TaskController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ToggleTest extends WebTestCase
{
    public function url()
    {
        yield ['/tasks/2/toggle'];
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
    /**
     * @dataProvider url
     */
    public function testError($url)
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/');
        $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        
        $this->assertEquals( 
            'http://localhost/', 
            $client->getRequest()->getUri()
        );
    }
}
