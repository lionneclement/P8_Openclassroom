<?php

namespace Tests\AccessControl;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnonyTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function urlFalse()
    {
        yield ['/'];
        yield ['/admin/tasks/anony'];
        yield ['/admin/users/list'];
        yield ['/admin/users/5/edit'];
        yield ['/users/edit'];
        yield ['/users/edit/password'];
        yield ['/logout'];
        yield ['/tasks'];
        yield ['/tasks/1/edit'];
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
        $this->client->request('GET', $url);
        $this->assertNotEquals(200, $this->client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider urlTrue
     */
    public function testTrue($url)
    {
        $this->client->request('GET', $url);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
