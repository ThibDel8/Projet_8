<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = new User();
        $user
            ->setUsername('Username_1')
            ->setPassword('password1234')
            ->setEmail('username_1@email.com')
            ->setRoles(['ROLE_USER']);

        $task = new Task();
        $task
            ->setTitle('Title_1')
            ->setContent('Content_1')
            ->setUser($user);

        $errors = $container->get('validator')->validate($task);

        $this->assertCount(0, $errors);
    }
}
