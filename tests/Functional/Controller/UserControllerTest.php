<?php

namespace App\Tests\Functional\Controller;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use App\Tests\FixturesTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testShowUsersList(): void
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $adminUser = $em->getRepository(User::class)->findOneBy(['username' => 'AdminUser']);

        $client->loginUser($adminUser);
        $client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
    }

    public function testCreateUser()
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $adminUser = $em->getRepository(User::class)->findOneBy(['username' => 'AdminUser']);

        $client->loginUser($adminUser);

        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'Username1',
            'user[password][first]' => 'password123',
            'user[password][second]' => 'password123',
            'user[email]' => 'username1@email.com',
            'user[roles]' => ['ROLE_USER', 'ROLE_ADMIN'],
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/users');

        $user = $em->getRepository(User::class)->findOneBy(['username' => 'Username1']);

        self::assertEquals('Username1', $user->getUsername());
        self::assertEquals('username1@email.com', $user->getEmail());
    }public function testCreateUserWithoutUsername()
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $adminUser = $em->getRepository(User::class)->findOneBy(['username' => 'AdminUser']);

        $client->loginUser($adminUser);

        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => '',
            'user[password][first]' => 'password123',
            'user[password][second]' => 'password123',
            'user[email]' => 'username1@email.com',
            'user[roles]' => ['ROLE_USER', 'ROLE_ADMIN'],
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/users/create');

        $user = $em->getRepository(User::class)->findOneBy(['username' => 'Username1']);

        self::assertSelectorTextContains('#user', 'Vous devez saisir un nom');
    }

    public function testEditUser()
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $adminUser = $em->getRepository(User::class)->findOneBy(['username' => 'AdminUser']);
        $user = $em->getRepository(User::class)->findOneBy(['username' => 'Auteur']);

        $client->loginUser($adminUser);

        $crawler = $client->request('GET', '/users/' . $user->getId() . '/edit');

        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'UsernameEdited',
            'user[password][first]' => 'password123',
            'user[password][second]' => 'password123',
            'user[email]' => 'username1@email.com',
            'user[roles]' => ['ROLE_USER'],
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/users');

        $user = $em->getRepository(User::class)->findOneBy(['username' => 'UsernameEdited']);

        self::assertEquals('UsernameEdited', $user->getUsername());
        self::assertEquals('username1@email.com', $user->getEmail());
    }

}
