<?php

namespace Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    private $user;
    private $task;

    public function setUp(): void
    {
        $this->user = new User();
        $this->task = new Task();
    }

    public function testEmail()
    {
        $this->user->setEmail('email@gmail.com');
        $this->assertSame('email@gmail.com', $this->user->getEmail());
    }

    public function testUsername()
    {
        $this->user->setUsername('username');
        $this->assertSame('username', $this->user->getUsername());
    }

    public function testRoles()
    {
        $this->user->setRoles(['ROLE_ADMIN']);
        $this->assertCount(2, $this->user->getRoles());
    }

    public function testGetTask()
    {
        $this->user->addTask($this->task);
        $this->assertCount(1, $this->user->getTasks());
    }

    public function testDeleteTask()
    {
        $this->user->removeTask($this->task);
        $this->assertCount(0, $this->user->getTasks());
    }
}