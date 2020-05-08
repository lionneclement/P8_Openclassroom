<?php

namespace Tests\Controller\UserController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditPasswordTest extends WebTestCase
{
    public function url()
    {
        yield ['/users/edit/password'];
    }
    /**
     * @dataProvider url
     */
    public function testSuccessForm($url)
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/create/users');
        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'testEditPassword';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'testEditPassword@gmail.com';
        $crawler = $client->submit($form);
        
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