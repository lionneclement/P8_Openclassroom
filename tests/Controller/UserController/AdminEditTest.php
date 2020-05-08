<?php

namespace Tests\Controller\UserController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminEditTest extends WebTestCase
{
    public function url()
    {
        yield ['/admin/users/15/edit'];
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
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $form = $crawler->selectButton('Modifier')->form();

        $form['admin_edit_user[username]'] = 'adminEdit';
        $form['admin_edit_user[password][first]'] = 'newPassword';
        $form['admin_edit_user[password][second]'] = 'newPassword';
        $form['admin_edit_user[email]'] = 'adminEdit@gmail.com';
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
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
              ]
        );
        $form = $crawler->selectButton('Modifier')->form();

        $form['admin_edit_user[password][first]'] = 'password';
        $form['admin_edit_user[password][second]'] = 'badPassword';
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('span.help-block')->count());
    }
}