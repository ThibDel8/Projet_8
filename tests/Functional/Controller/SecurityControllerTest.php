<?php

namespace App\Tests\Functional\Controller;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use App\Tests\FixturesTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testLoginPageIsSuccessful()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('form[action="/login_check"]');
    }

    public function testLoginWithInvalidCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'invalid_user',
            '_password' => 'invalid_password',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/login');
        $client->followRedirect();

        $this->assertSelectorExists('button', 'Se connecter');
    }

    public function testLoginCheckWithValidCredentials()
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $crawler = $client->request('GET', '/login');
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $user = $em->getRepository(User::class)->findOneBy(['username' => 'AdminUser']);

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => $user->getUsername(),
            '_password' => 'password1234',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/');
    }

    public function testLoginCheckWithUnvalidCredentials()
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $crawler = $client->request('GET', '/login');
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $user = $em->getRepository(User::class)->findOneBy(['username' => 'AdminUser']);

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => $user->getUsername(),
            '_password' => 'bad_password',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/login');
    }

    public function testLogout()
    {
        $client = static::createClient();
        $client->request('GET', '/logout');

        $this->assertResponseRedirects('/');
    }
}
