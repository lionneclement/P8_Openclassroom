<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminEditUserType;
use App\Form\ProfilPasswordType;
use App\Form\ProfilType;
use App\Form\UserType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/list", name="user_list")
     */
    public function listAction()
    {
        return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository('App:User')->findAll()]);
    }

    /**
     * @Route("/create/users", name="user_create")
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/users", name="user_edit")
     */
    public function editAction(UserInterface $user, Request $request)
    {
        $form = $this->createForm(ProfilType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
    /**
     * @Route("/edit/users/password", name="user_edit_password")
     */
    public function editPasswordAction(UserInterface $user, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(ProfilPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Le mot de passe a bien été modifié");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/editPassword.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
    /**
     * @Route("/admin/users/{id}/edit", name="admin_edit_user")
     */
    public function adminEditAction(User $user, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(AdminEditUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/adminEdit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
    /**
     * @Route("/admin/users/{id}/delete", name="admin_delete_user")
     */
    public function adminDeleteUser(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', "L'utilisateur a bien été supprimée.");

        return $this->redirectToRoute('homepage');
    }
}
