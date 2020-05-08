<?php

namespace Tests\AccessControl;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminTest extends WebTestCase
{
    public function urlFalse()
    {
        yield ['/users/create'];
    }
    public function urlTrue()
    {
        yield ['/'];
        yield ['/users/edit'];
        yield ['/users/edit/password'];
        yield ['/users/list'];
        yield ['/admin/users/14/edit'];
    }
    /**
     * @dataProvider urlFalse
     */
    public function testFalse($url)
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
     * @dataProvider urlTrue
     */
    public function testTrue($url)
    {
        $client = static::createClient();
        $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
            ]
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}