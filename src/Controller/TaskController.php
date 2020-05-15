<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction(UserInterface $user)
    {
        $tasks = $this->getDoctrine()
            ->getRepository('App:Task')
            ->findBy(['userId' => $user->getId()]);
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }
    /**
     * @Route("/tasks/{id}/isDone", name="task_list_isDone", requirements={"id"="[01]"})
     */
    public function listActionIsDone(int $id, UserInterface $user)
    {
        $tasks = $this->getDoctrine()
            ->getRepository('App:Task')
            ->findBy(['userId' => $user->getId(), 'isDone'=> $id]);
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request, UserInterface $user)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setUserId($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit", requirements={"id"="\d+"})
     */
    public function editAction(Task $task, Request $request, UserInterface $user)
    {
        if ($task->getUserId() != $user) {
            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render(
            'task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
            ]
        );
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle", requirements={"id"="\d+"})
     */
    public function toggleTaskAction(Task $task, Request $request, UserInterface $user)
    {
        if ($task->getUserId() != $user) {
            return $this->redirectToRoute('homepage');
        }
        $task->setIsDone(!$task->getIsDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été modifier.', $task->getTitle()));

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete", requirements={"id"="\d+"})
     */
    public function deleteTaskAction(Task $task, Request $request, UserInterface $user)
    {
        if ($task->getUserId() != $user) {
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirect($request->headers->get('referer'));
    }
}
