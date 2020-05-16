<?php

namespace Tests\Controller\SecurityController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogoutTest extends WebTestCase
{
    private $urlLogin = '/login';

    public function url()
    {
        yield ['/logout'];
    }
    /**
     * @dataProvider url
     */
    public function testConnected($url)
    {
        $client = static::createClient();
        $client->request(
            'GET', $this->urlLogin, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $client->request('GET', $url);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider url
     */
    public function testNotConnected($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
