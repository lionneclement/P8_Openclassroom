<?php

namespace Tests\AccessControl;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function urlFalse()
    {
        yield ['/users/create'];
        yield ['/login'];
        yield ['/tasks/1/edit'];//false because task is connected to user
    }
    public function urlTrue()
    {
        yield ['/tasks'];
        yield ['/'];
        yield ['/users/edit'];
        yield ['/users/edit/password'];
        yield ['/admin/users/list'];
        yield ['/admin/users/5/edit'];
        yield ['/admin/users/5/editPassword'];
        yield ['/admin/tasks/anony'];
    }
    /**
     * @dataProvider urlFalse
     */
    public function testFalse($url)
    {
        $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
            ]
        );
        $this->assertNotEquals(200, $this->client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider urlTrue
     */
    public function testTrue($url)
    {
        $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
            ]
        );
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
