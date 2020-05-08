<?php

namespace Tests\AccessControl;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnonyTest extends WebTestCase
{
    public function urlFalse()
    {
        yield ['/'];
        yield ['/users/list'];
        yield ['/admin/users/14/edit'];
        yield ['/users/edit'];
        yield ['/users/edit/password'];
        yield ['/logout'];
        yield ['/tasks'];
    }
    public function urlTrue()
    {
        yield ['/login'];
        yield ['/users/create'];
    }
    /**
     * @dataProvider urlFalse
     */
    public function testFalse($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider urlTrue
     */
    public function testTrue($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}