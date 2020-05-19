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
    public function url()
    {
        yield ['/tasks/3/delete'];
    }
    /**
     * @dataProvider url
     */
    public function testError($url)
    {
        $this->client->request(
            'GET', '/', [], [], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $this->client->request('GET', $url);

        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !');
    }
    /**
     * @dataProvider url
     */
    public function testSuccess($url)
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
