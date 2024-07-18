<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:assign-user-to-tasks',
    description: 'Assigns an anonymous user to tasks that are currently unassigned.',
)]
class AssignUserToTasksCommand extends Command
{
    private $em;
    private $taskRepository;
    private $userRepository;

    public function __construct(EntityManagerInterface $em, TaskRepository $taskRepository, UserRepository $userRepository)
    {
        parent::__construct();
        $this->em = $em;
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this->setDescription('Assigns an anonymous user to tasks that are currently unassigned.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tasks = $this->taskRepository->findAll();

        $anonymousUser = $this->userRepository->findOneBy(['username' => 'Anonyme']);
        if (!$anonymousUser) {
            $anonymousUser = new User();
            $anonymousUser
                ->setUsername('Anonyme')
                ->setEmail('anonymous-user@email.com')
                ->setPassword(password_hash('password1234', PASSWORD_BCRYPT));

            $this->em->persist($anonymousUser);
            $this->em->flush();
            $output->writeln('Utilisateur anonyme créé.');
        }

        foreach ($tasks as $task) {
            if (null === $task->getUser()) {
                $task->setUser($anonymousUser);
                $this->em->persist($task);
            }
        }

        $this->em->flush();
        $output->writeln('L\'utilisateur anonyme a été attribué aux tâches non assignées avec succès.');

        return Command::SUCCESS;
    }
}
