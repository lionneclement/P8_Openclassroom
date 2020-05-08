<?php

namespace Tests\AccessControl;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminTest extends WebTestCase
{
    public function urlFalse()
    {
        yield ['/create/users'];
        yield ['/login'];
    }
    public function urlTrue()
    {
        yield ['/tasks'];
        yield ['/'];
        yield ['/users/edit'];
        yield ['/users/edit/password'];
        yield ['/admin/list'];
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