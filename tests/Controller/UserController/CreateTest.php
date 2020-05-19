<?php

namespace Tests\Controller\UserController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }
    public function url()
    {
        yield ['/users/create'];
    }
    /**
     * @dataProvider url
     */
    public function testSuccessForm($url)
    {
        $crawler = $this->client->request('GET', $url);
        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'test';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'test@gmail.com';
        $crawler = $this->client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('div.alert-success')->count());
    }
    /**
     * @dataProvider url
     */
    public function testErrorPasswordForm($url)
    {
        $crawler = $this->client->request('GET', $url);
        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'test';
        $form['user[password][first]'] = 'password1';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'test@gmail.com';
        $crawler = $this->client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('span.help-block')->count());
    }
    /**
     * @dataProvider url
     */
    public function testErrorDuplicateEmailForm($url)
    {
        $crawler = $this->client->request('GET', $url);
        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'test';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'test@gmail.com';
        $crawler = $this->client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('span.help-block')->count());
    }
}
