<?php

namespace Tests\Controller\UserController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditProfilTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }
    public function login($url)
    {
        return $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'testEdit@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
    }
    public function url()
    {
        yield ['/users/edit'];
    }
    /**
     * @dataProvider url
     */
    public function testSuccessForm($url)
    {
        $crawler = $this->login($url);
        $form = $crawler->selectButton('Modifier')->form();

        $form['profil[username]'] = 'testEdit';
        $form['profil[email]'] = 'testEdit@gmail.com';
        $crawler = $this->client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
    }
    /**
     * @dataProvider url
     */
    public function testErrorAlreadyEmailUsedForm($url)
    {
        $crawler = $this->login($url);
        $form = $crawler->selectButton('Modifier')->form();

        $form['profil[username]'] = 'editTest';
        $form['profil[email]'] = 'user@gmail.com';
        $crawler = $this->client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('span.help-block')->count());
    }
}
