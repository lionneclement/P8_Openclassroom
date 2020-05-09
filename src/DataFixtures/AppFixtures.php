<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public static function getGroups(): array
    {
        return ['app'];
    }
    public function load(ObjectManager $manager)
    {
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

        for ($i = 1; $i <= 10; $i++) {
            $task = new Task();
            $task->setTitle('Task Title n'.$i);
            $task->setContent('Task Content n'.$i);
            $task->setUserId($user);
            $manager->persist($task);
        }

        $manager->flush();
    }
}
