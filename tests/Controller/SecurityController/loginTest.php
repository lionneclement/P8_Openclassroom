<?php

namespace Tests\Controller\SecurityController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }
    public function url()
    {
        yield ['/login'];
    }
    /**
     * @dataProvider url
     */
    public function testErrorForm($url)
    {
        $crawler = $this->client->request('GET', $url);
        $form = $crawler->selectButton('Sign in')->form();

        $form['email'] = 'error@gmail.com';
        $form['password'] = 'error';
        $crawler = $this->client->submit($form);
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('div.alert-danger')->count());
    }
    /**
     * @dataProvider url
     */
    public function testRedirectForm($url)
    {
        $crawler = $this->client->request('GET', $url);
        $form = $crawler->selectButton('Sign in')->form();

        $form['email'] = 'user@gmail.com';
        $form['password'] = 'password';
        $crawler = $this->client->submit($form);

        $this->assertEquals(1, $crawler->selectLink('Profile')->count());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider url
     */
    public function testAlreadyConnected($url)
    {
        $this->client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
            ]
        );

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }
}
