<?php

namespace Tests\Controller\UserController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateTest extends WebTestCase
{
    public function url()
    {
        yield ['/create/users'];
    }
    /**
     * @dataProvider url
     */
    public function testSuccessForm($url)
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $url);
        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'test';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'test@gmail.com';
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
    }
    /**
     * @dataProvider url
     */
    public function testErrorPasswordForm($url)
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $url);
        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'test';
        $form['user[password][first]'] = 'password1';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'test@gmail.com';
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('span.help-block')->count());
    }
    /**
     * @dataProvider url
     */
    public function testErrorDuplicateEmailForm($url)
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $url);
        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'test';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'test@gmail.com';
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('span.help-block')->count());
    }
}
