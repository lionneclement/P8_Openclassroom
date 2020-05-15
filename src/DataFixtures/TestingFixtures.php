<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class TestingFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public static function getGroups(): array
    {
        return ['testing'];
    }
    public function load(ObjectManager $manager)
    {
        $connection = $manager->getConnection();
        $connection->exec("ALTER TABLE user AUTO_INCREMENT = 1");
        $connection->exec("ALTER TABLE task AUTO_INCREMENT = 1");

        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@gmail.com');
        $user->setRoles(['ROLE_ADMIN']);
        $password = $this->encoder->encodePassword($user, 'password');
        $user->setPassword($password);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $password = $this->encoder->encodePassword($user, 'password');
        $user->setPassword($password);
        $manager->persist($user);

        $task = new Task();
        $task->setTitle('userTaskTitle');
        $task->setContent('userTaskContent');
        $task->setUserId($user);
        $manager->persist($task);

        $task = new Task();
        $task->setTitle('toggleTest');
        $task->setContent('toggleTest');
        $task->setUserId($user);
        $manager->persist($task);

        $task = new Task();
        $task->setTitle('DeleteTask');
        $task->setContent('DeleteTask');
        $task->setUserId($user);
        $manager->persist($task);

        $user = new User();
        $user->setUsername('testEditPassword');
        $user->setEmail('testEditPassword@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $password = $this->encoder->encodePassword($user, 'password');
        $user->setPassword($password);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('testEdit');
        $user->setEmail('testEdit@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $password = $this->encoder->encodePassword($user, 'password');
        $user->setPassword($password);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('adminEdit');
        $user->setEmail('adminEdit@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $password = $this->encoder->encodePassword($user, 'password');
        $user->setPassword($password);
        $manager->persist($user);

        $manager->flush();
    }
}
