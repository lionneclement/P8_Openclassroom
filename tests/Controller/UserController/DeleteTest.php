<?php

namespace Tests\Controller\UserController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function url()
    {
        yield ['/admin/users/6/delete'];
    }
    /**
     * @dataProvider url
     */
    public function testError($url)
    {
        $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider url
     */
    public function testSuccess($url)
    {
        $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}
