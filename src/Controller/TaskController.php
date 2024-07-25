<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route(path: '/tasks', name: 'task_list')]
    public function list(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findAll();

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route(path: '/tasks/create', name: 'task_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        if (empty($this->getUser())) {
            $this->addFlash('error', 'Vous devez être connecté pour créer une tâche.');

            return $this->redirectToRoute('login');
        }
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/tasks/{id}/edit', name: 'task_edit')]
    public function edit(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        $currentUser = $this->getUser();
        $author = $task->getUser();

        if ($author !== $currentUser) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route(path: '/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTask(Task $task, EntityManagerInterface $em): Response
    {
        $task->toggle(!$task->isDone());
        $em->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route(path: '/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTask(Task $task, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $author = $task->getUser();
        $isAnonymeAuthor = 'Anonyme' === $task->getUser()->getUsername();
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        if ($author === $user || ($isAnonymeAuthor && $isAdmin)) {
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée !');
        } else {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer cette tâche !');

            return $this->redirectToRoute('error403');
        }

        return $this->redirectToRoute('task_list');
    }
}
