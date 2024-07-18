<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait FixturesTrait
{
    protected function loadFixtures(array $classNames = [], bool $append = false): ReferenceRepository
    {
        $container = self::getContainer();
        $objectManager = $container->get(EntityManagerInterface::class);
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        $loader = new \Doctrine\Common\DataFixtures\Loader();

        foreach ($classNames as $className) {
            if (\App\DataFixtures\AppFixtures::class === $className) {
                $fixture = new $className($passwordHasher);
            } else {
                $fixture = new $className();
            }
            $loader->addFixture($fixture);
        }

        $purger = new ORMPurger($objectManager);
        $executor = new ORMExecutor($objectManager, $purger);

        if (!$append) {
            $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        }

        $executor->execute($loader->getFixtures());

        return new ReferenceRepository($objectManager);
    }
}
