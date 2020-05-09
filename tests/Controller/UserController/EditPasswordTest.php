<?php

namespace Tests\Controller\UserController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditPasswordTest extends WebTestCase
{
    public function url()
    {
        yield ['/edit/users/password'];
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
            'PHP_AUTH_USER' => 'testEditPassword@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $form = $crawler->selectButton('Modifier')->form();

        $form['profil_password[password][first]'] = 'password';
        $form['profil_password[password][second]'] = 'password';
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
    }
    /**
     * @dataProvider url
     */
    public function testErrorForm($url)
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'testEditPassword@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $form = $crawler->selectButton('Modifier')->form();

        $form['profil_password[password][first]'] = 'Password';
        $form['profil_password[password][second]'] = 'badPassword';
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('span.help-block')->count());
    }
}
