<?php

namespace Tests\Controller\TaskController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function url()
    {
        yield ['/tasks/1/edit'];
    }
    public function urlTaskAnony()
    {
        yield ['/tasks/4/edit'];
    }
    /**
     * @dataProvider url
     */
    public function testSuccessForm($url)
    {
        $this->client->followRedirects();
        $crawler = $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $form = $crawler->selectButton('Modifier')->form();

        $form['task[title]'] = 'EditTaskTitle';
        $form['task[content]'] = 'EditTaskContent';
        $crawler = $this->client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
    }
    /**
     * @dataProvider urlTaskAnony
     */
    public function testTaskAnonySuccess($url)
    {
        $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider urlTaskAnony
     */
    public function testTaskAnonyError($url)
    {
        $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );

        $this->assertNotEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
