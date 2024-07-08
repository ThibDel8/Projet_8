<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasherInterface)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $author = new User();
        $author->setUsername('Auteur')
            ->setEmail('auteur@email.com')
            ->setPassword($this->passwordHasherInterface->hashPassword($author, 'password1234'))
            ->setRoles(['ROLE_USER']);

        $anonymeUser = new User();
        $anonymeUser->setUsername('Anonyme')
            ->setEmail('anonyme@email.com')
            ->setPassword($this->passwordHasherInterface->hashPassword($anonymeUser, 'password1234'))
            ->setRoles(['ROLE_USER']);

        $adminUser = new User();
        $adminUser->setUsername('AdminUser')
            ->setEmail('admin-user@email.com')
            ->setPassword($this->passwordHasherInterface->hashPassword($adminUser, 'password1234'))
            ->setRoles(['ROLE_ADMIN']);

        $task = new Task();
        $task->setTitle('Title')
            ->setContent('This is a content.')
            ->setUser($author);

        $manager->persist($author);
        $manager->persist($adminUser);
        $manager->persist($anonymeUser);
        $manager->persist($task);
        $manager->flush();
    }
}
