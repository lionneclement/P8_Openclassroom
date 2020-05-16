<?php

namespace Tests\Controller\TaskController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditTest extends WebTestCase
{
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
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $form = $crawler->selectButton('Modifier')->form();

        $form['task[title]'] = 'EditTaskTitle';
        $form['task[content]'] = 'EditTaskContent';
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
    }
    /**
     * @dataProvider urlTaskAnony
     */
    public function testTaskAnonySuccess($url)
    {
        $client = static::createClient();
        $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider urlTaskAnony
     */
    public function testTaskAnonyError($url)
    {
        $client = static::createClient();
        $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );

        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());
    }
}
