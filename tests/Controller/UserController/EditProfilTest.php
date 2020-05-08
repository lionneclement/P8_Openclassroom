<?php

namespace Tests\Controller\UserController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditProfilTest extends WebTestCase
{
    public function url()
    {
        yield ['/users/edit'];
    }
    /**
     * @dataProvider url
     */
    public function testSuccessForm($url)
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'testEdit';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'testEdit@gmail.com';
        $crawler = $client->submit($form);
        
        $crawler = $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'testEdit@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $form = $crawler->selectButton('Modifier')->form();

        $form['profil[username]'] = 'editTest';
        $form['profil[email]'] = 'editTest@gmail.com';
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
    }
}