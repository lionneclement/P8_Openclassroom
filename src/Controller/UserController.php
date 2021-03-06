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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/users/list", name="admin_user_list")
     */
    public function list(): Response
    {
        return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository('App:User')->findAll()]);
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function create(Request $request, UserPasswordEncoderInterface $encoder): Response
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
     * @Route("/users/edit", name="user_edit")
     */
    public function edit(UserInterface $user, Request $request): Response
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
     * @Route("/users/edit/password", name="user_edit_password")
     */
    public function editPassword(UserInterface $user, Request $request, UserPasswordEncoderInterface $encoder): Response
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
     * @Route("/admin/users/{id}/edit", name="admin_edit_user", requirements={"id"="\d+"})
     */
    public function adminEditUser(User $user, Request $request): Response
    {
        $form = $this->createForm(AdminEditUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('user/adminEdit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
    /**
     * @Route("/admin/users/{id}/editPassword", name="admin_edit_user_password", requirements={"id"="\d+"})
     */
    public function adminEditUserPassword(User $user, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(ProfilPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('user/editPassword.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
    /**
     * @Route("/admin/users/{id}/delete", name="admin_delete_user", requirements={"id"="\d+"})
     */
    public function adminDeleteUser(User $user): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', "L'utilisateur a bien été supprimée.");

        return $this->redirectToRoute('homepage');
    }
}
