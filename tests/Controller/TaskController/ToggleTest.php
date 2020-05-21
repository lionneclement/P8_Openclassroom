<?php

namespace Tests\Controller\TaskController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ToggleTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }
    public function url()
    {
        yield ['/tasks/2/toggle'];
    }
    public function urlError()
    {
        yield ['/tasks/4/toggle'];
    }
    /**
     * @dataProvider url
     */
    public function testSuccessUser($url)
    {
        $crawler = $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );

        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
    }
    /**
     * @dataProvider urlError
     */
    public function testErrorUser($url)
    {
        $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );

        $this->assertNotEquals(200, $this->client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider url
     */
    public function testSuccessAdmin($url)
    {
        $crawler = $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );

        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
    }
}
