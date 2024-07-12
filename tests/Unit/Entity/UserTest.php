<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    public function testUsernameCanBeSet(): void
    {
        $user = new User();
        $user->setUsername('Username_1');

        $this->assertEquals('Username_1', $user->getUsername());
    }

    public function testEmailCanBeSet(): void
    {
        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);

        $this->assertEquals($email, $user->getEmail());
    }

    public function testPasswordCanBeSet(): void
    {
        $user = new User();
        $password = 'password123';
        $user->setPassword($password);

        $this->assertEquals($password, $user->getPassword());
    }

    public function testRolesCanBeSet(): void
    {
        $user = new User();
        $roles = ['ROLE_ADMIN'];
        $user->setRoles($roles);

        $resultingRoles = $user->getRoles();

        $this->assertContains('ROLE_ADMIN', $resultingRoles);
        $this->assertContains('ROLE_USER', $resultingRoles);

        $this->assertCount(2, $resultingRoles);
    }

    public function testGetUserIdentifier(): void
    {
        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);

        $this->assertEquals($email, $user->getUserIdentifier());
    }

    public function testDefaultRoleUserIsPresent(): void
    {
        $user = new User();
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testEraseCredentials(): void
    {
        $user = new User();
        $user->eraseCredentials();

        $this->assertTrue(true);
    }

    public function testEmailValidation(): void
    {
        $user = new User();
        $user
            ->setUsername('username')
            ->setEmail('invalid-email');

        $violations = $this->validator->validate($user);

        $this->assertGreaterThan(0, count($violations));
        $this->assertEquals("Le format de l'adresse n'est pas correct.", $violations[0]->getMessage());
    }

    public function testUsernameValidation(): void
    {
        $user = new User();
        $user
            ->setEmail('test@example.com')
            ->setUsername('');

        $violations = $this->validator->validate($user);

        $this->assertCount(1, $violations);
        $this->assertEquals("Vous devez saisir un nom d'utilisateur.", $violations[0]->getMessage());
    }
}
