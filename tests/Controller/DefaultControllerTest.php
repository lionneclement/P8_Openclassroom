<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testAnony($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider urlProvider
     */
    public function testUser($url)
    {
        $client = static::createClient();
        $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'lionneclement@gmail.com',
            'PHP_AUTH_PW'   => 'password',
            ]
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function urlProvider()
    {
        yield ['/'];
    }
}
