<?php

namespace Tests\Controller\TaskController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditTest extends WebTestCase
{
  public function url()
  {
      yield ['/users/tasks/1/edit'];
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
}