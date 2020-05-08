<?php

namespace Tests\Controller\SecurityController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
  public function url()
  {
      yield ['/login'];
  }
  /**
   * @dataProvider url
   */
  public function testErrorForm($url)
  {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $url);
        $form = $crawler->selectButton('Sign in')->form();

        $form['email'] = 'error@gmail.com';
        $form['password'] = 'error';
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('div.alert-danger')->count());
  }
  /**
   * @dataProvider url
   */
  public function testRedirectForm($url)
  {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $url);
        $form = $crawler->selectButton('Sign in')->form();

        $form['email'] = 'user@gmail.com';
        $form['password'] = 'password';
        $crawler = $client->submit($form);
        
        $this->assertStringContainsString('/', $client->getRequest()->getUri());
  }
  /**
   * @dataProvider url
   */
  public function testAlreadyConnected($url)
  {
        $client = static::createClient();
        $client->followRedirects();
        $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
            ]
        );
        $this->assertStringContainsString('/', $client->getRequest()->getUri());
  }
}