<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Security\Voter\TaskVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

final class TaskController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private TranslatorInterface $translatorInterface, private Security $security) {}
    #[Route('/', name: 'app_task_index', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findBy(['user' => $this->getUser()]);
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
            $this->entityManager->persist($task);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translatorInterface->trans('flash.success.task.create'));

            return $this->redirectToRoute(route: 'app_task_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function show(Task $task): Response
    {
        if (!$this->security->isGranted(TaskVoter::SHOW, $task)) {
            throw $this->createAccessDeniedException($this->translatorInterface->trans('access_denied'));
        }

        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]

    public function edit(Request $request, Task $task): Response
    {
        if (!$this->security->isGranted(TaskVoter::EDIT, $task)) {
            throw $this->createAccessDeniedException($this->translatorInterface->trans('access_denied'));
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translatorInterface->trans('flash.success.task.update'));

            return $this->redirectToRoute(route: 'app_task_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if (!$this->security->isGranted(TaskVoter::DELETE, $task)) {
            throw $this->createAccessDeniedException($this->translatorInterface->trans('access_denied'));
        }
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
            $this->addFlash('success', $this->translatorInterface->trans('flash.success.task.delete'));
        }

        return $this->redirectToRoute(route: 'app_task_index', status: Response::HTTP_SEE_OTHER);
    }
}
