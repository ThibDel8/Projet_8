<?php

namespace App\Tests\Functional\Controller;

use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Entity\User;
use App\Tests\FixturesTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testTaskListPageIsAccessible(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
    }

    public function testCreateTaskPageIsAccessible(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();
    }

    public function testCreateTask(): void
    {

        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $container = self::getContainer();
        $em = $container->get(EntityManagerInterface::class);
        $author = $em->getRepository(User::class)->findOneBy(['username' => 'Auteur']);

        $this->assertNotNull($author);
        $client->loginUser($author);

        $crawler = $client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([

            'task[title]' => 'Test Task',
            'task[content]' => 'This is a test task',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/tasks');

        $client->followRedirect();

        $this->assertSelectorTextContains('.alert-success', 'La tâche a bien été ajoutée.');

    }

    public function testEditTaskByAdminOrTheAuthor(): void
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $container = self::getContainer();
        $em = $container->get(EntityManagerInterface::class);

        $user = $em->getRepository(User::class)->findOneBy(['username' => 'Auteur']);
        $task = $em->getRepository(Task::class)->findOneBy(['title' => 'Title']);

        $client->loginUser($user);

        $this->assertSame($user, $task->getUser());

        $crawler = $client->request('GET', '/tasks/' . $task->getId() . '/edit');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Updated Test Task',
            'task[content]' => 'This is an updated test task',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/tasks');

        $client->followRedirect();

        $updatedTask = $em->getRepository(Task::class)->find($task->getId());
        $this->assertSame('Updated Test Task', $updatedTask->getTitle());
        $this->assertSame('This is an updated test task', $updatedTask->getContent());
        $this->assertSame($user->getId(), $updatedTask->getUser()->getId());
        $task->setUser($user);

        $this->assertSelectorTextContains('.alert-success', 'La tâche a bien été modifiée.');

    }

    public function testUnauthorizedUserCannotEditTask(): void
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $container = self::getContainer();
        $em = $container->get(EntityManagerInterface::class);

        $user = $em->getRepository(User::class)->findOneBy(['username' => 'Anonyme']);
        $task = $em->getRepository(Task::class)->findOneBy(['title' => 'Title']);

        $client->loginUser($user);

        $client->request('GET', '/tasks/' . $task->getId() . '/edit');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testToggleTask(): void
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $container = self::getContainer();
        $em = $container->get(EntityManagerInterface::class);

        $user = $em->getRepository(User::class)->findOneBy(['username' => 'Auteur']);
        $task = $em->getRepository(Task::class)->findOneBy(['title' => 'Title']);

        $client->loginUser($user);

        $client->request('GET', '/tasks/' . $task->getId() . '/toggle');

        $em->refresh($task);

        $this->assertTrue($task->isDone(), 'La tâche devrait être marquée comme faite après avoir basculé.');

        $this->assertResponseRedirects('/tasks');

        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('.alert-success', 'La tâche Title a bien été marquée comme faite.');

    }

    public function testDeleteTaskByAdminOrTheAuthor(): void
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $em = self::getContainer()->get(EntityManagerInterface::class);

        $adminUser = $em->getRepository(User::class)->findOneBy(['username' => 'AdminUser']);
        $task = $em->getRepository(Task::class)->findOneBy(['title' => 'Title']);

        $client->loginUser($adminUser);

        $client->request('GET', '/tasks/' . $task->getId() . '/delete');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains('.alert-success', 'La tâche a bien été supprimée !');

    }

    public function testUnauthorizedUserCannotDeleteTask(): void
    {
        $client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $em = self::getContainer()->get(EntityManagerInterface::class);

        $user = $em->getRepository(User::class)->findOneBy(['username' => 'Anonyme']);
        $task = $em->getRepository(Task::class)->findOneBy(['title' => 'Title']);

        $client->loginUser($user);

        $client->request('GET', '/tasks/' . $task->getId() . '/delete');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
