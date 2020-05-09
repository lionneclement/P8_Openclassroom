<?php

namespace Tests\Controller\TaskController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateTest extends WebTestCase
{
  public function url()
  {
      yield ['/users/tasks/create'];
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
        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]'] = 'taskTitle';
        $form['task[content]'] = 'taskContent';
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
  }
}