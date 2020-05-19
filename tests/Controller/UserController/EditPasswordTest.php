<?php

namespace Tests\Controller\UserController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditPasswordTest extends WebTestCase
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
            'PHP_AUTH_USER' => 'testEditPassword@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
    }
    public function url()
    {
        yield ['/users/edit/password'];
    }
    /**
     * @dataProvider url
     */
    public function testSuccessForm($url)
    {
        $crawler = $this->login($url);
        $form = $crawler->selectButton('Modifier')->form();

        $form['profil_password[password][first]'] = 'password';
        $form['profil_password[password][second]'] = 'password';
        $crawler = $this->client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
    }
    /**
     * @dataProvider url
     */
    public function testErrorForm($url)
    {
        $crawler = $this->login($url);
        $form = $crawler->selectButton('Modifier')->form();

        $form['profil_password[password][first]'] = 'Password';
        $form['profil_password[password][second]'] = 'badPassword';
        $crawler = $this->client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('span.help-block')->count());
    }
}
