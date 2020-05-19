<?php

namespace Tests\Controller\UserController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminEditTest extends WebTestCase
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
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
    }
    public function url()
    {
        yield ['/admin/users/5/edit'];
    }
    /**
     * @dataProvider url
     */
    public function testSuccessForm($url)
    {
        $crawler = $this->login($url);
        $form = $crawler->selectButton('Modifier')->form();

        $form['admin_edit_user[username]'] = 'editAdmin';
        $form['admin_edit_user[password][first]'] = 'newPassword';
        $form['admin_edit_user[password][second]'] = 'newPassword';
        $form['admin_edit_user[email]'] = 'editAdmin@gmail.com';
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

        $form['admin_edit_user[password][first]'] = 'password';
        $form['admin_edit_user[password][second]'] = 'badPassword';
        $crawler = $this->client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('span.help-block')->count());
    }
}
