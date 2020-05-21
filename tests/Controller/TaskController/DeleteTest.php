<?php

namespace Tests\Controller\TaskController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }
    public function urlUser()
    {
        yield ['/tasks/3/delete'];
    }
    public function urlAdmin()
    {
        yield ['/tasks/5/delete'];
    }
    /**
     * @dataProvider urlAdmin
     */
    public function testSuccessAdmin($url)
    {
        $this->client->request(
            'GET', '/', [], [], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $crawler = $this->client->request('GET', $url);

        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
    }
    /**
     * @dataProvider urlUser
     */
    public function testSuccessUser($url)
    {
        $this->client->request(
            'GET', '/', [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $crawler = $this->client->request('GET', $url);

        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
    }
}
