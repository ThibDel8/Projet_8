<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    public function testTitleCanBeSet(): void
    {
        $task = new Task();
        $task->setTitle('Test Title');

        $this->assertEquals('Test Title', $task->getTitle());
    }

    public function testContentCanBeSet(): void
    {
        $task = new Task();
        $task->setContent('This is the task content.');

        $this->assertEquals('This is the task content.', $task->getContent());
    }

    public function testIsDoneDefaultValue(): void
    {
        $task = new Task();
        $this->assertFalse($task->isDone());
    }

    public function testToggle(): void
    {
        $task = new Task();
        $task->toggle(true);
        $this->assertTrue($task->isDone());

        $task->toggle(false);
        $this->assertFalse($task->isDone());
    }

    public function testUserCanBeSet(): void
    {
        $task = new Task();
        $user = new User();
        $task->setUser($user);

        $this->assertSame($user, $task->getUser());
    }

    public function testTitleValidation(): void
    {
        $task = new Task();
        $task->setContent('Valid content.');

        $violations = $this->validator->validate($task);
        $this->assertCount(1, $violations);
        $this->assertEquals("Vous devez saisir un titre.", $violations[0]->getMessage());
    }

    public function testContentValidation(): void
    {
        $task = new Task();
        $task->setTitle('Valid Title');

        $violations = $this->validator->validate($task);
        $this->assertCount(1, $violations);
        $this->assertEquals("Vous devez saisir du contenu.", $violations[0]->getMessage());
    }
}
