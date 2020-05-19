<?php

namespace Tests\Controller\SecurityController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogoutTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }
    public function url()
    {
        yield ['/logout'];
    }
    /**
     * @dataProvider url
     */
    public function testConnected($url)
    {
        $this->client->request(
            'GET', '/', [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $crawler = $this->client->request('GET', $url);

        $this->assertEquals(1, $crawler->selectButton('Sign in')->count());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider url
     */
    public function testNotConnected($url)
    {
        $crawler = $this->client->request('GET', $url);

        $this->assertEquals(1, $crawler->selectButton('Sign in')->count());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
