<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function list(UserInterface $user): Response
    {
        $arrayFind = $this->isGranted('ROLE_ADMIN')?[]:['userId' => $user]; 
        
        $tasks = $this->getDoctrine()
            ->getRepository('App:Task')
            ->findBy($arrayFind);
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }
    /**
     * @Route("/tasks/{id}/list", name="task_list_isDone", requirements={"id"="[01]"})
     */
    public function listIsDone(int $id, UserInterface $user): Response
    {
        $arrayFind = $this->isGranted('ROLE_ADMIN')?[]:['userId' => $user]; 

        $tasks = $this->getDoctrine()
            ->getRepository('App:Task')
            ->findBy($arrayFind+=['isDone'=> $id]);
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function create(Request $request, UserInterface $user): Response
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
    public function edit(Task $task, Request $request, UserInterface $user): Response
    {
        if ($task->getUserId()!=$user) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
    public function toggleTask(Task $task, Request $request, UserInterface $user): Response
    {
        if ($task->getUserId()!=$user) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        $task->setIsDone(!$task->getIsDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été modifier.', $task->getTitle()));

        if ($request->headers->get('referer')) {
            return $this->redirect($request->headers->get('referer'));
        }
        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete", requirements={"id"="\d+"})
     */
    public function deleteTask(Task $task, Request $request, UserInterface $user): Response
    {
        if ($task->getUserId()!=$user) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        if ($request->headers->get('referer')) {
            return $this->redirect($request->headers->get('referer'));
        }
        return $this->redirectToRoute('task_list');
    }
    /**
     * @Route("/admin/tasks/anony", name="task_list_anony")
     */
    public function listTaskAnony(): Response
    {
        $user = $this->getDoctrine()
            ->getRepository('App:User')
            ->findBy(['email'=>'anonymous@gmail.com']);

        $tasks = $this->getDoctrine()
            ->getRepository('App:Task')
            ->findBy(['userId'=> $user]);
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }
}
