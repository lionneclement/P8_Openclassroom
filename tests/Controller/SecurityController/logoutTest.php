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
        $client->followRedirects();
        $client->request(
            'GET', $this->urlLogin, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $client->request('GET', $url);

        $this->assertStringContainsString($this->urlLogin, $client->getRequest()->getUri());
    }
    /**
     * @dataProvider url
     */
    public function testNotConnected($url)
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', $url);

        $this->assertStringContainsString($this->urlLogin, $client->getRequest()->getUri());
    }
}