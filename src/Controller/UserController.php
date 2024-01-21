<?php

namespace App\Controller;

use App\Entity\UserAccount;
use App\Form\UserForm;
use App\Repository\UserDoctrineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class UserController extends AbstractController
{

    public function user_edit(UserAccount $user, UserDoctrineRepository $userRepository): Response
    {
        return $this->render('user/list.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'edit_mode' => true,
        ]);
    }
    public function user_save(UserAccount $user, UserDoctrineRepository $userRepository): Response
    {
        //update the user account
        $users = $userRepository->findAll();
        return $this->render('user/list.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }
    public function user_delete(UserAccount $user, EntityManagerInterface $em, UserDoctrineRepository $userRepository): Response
    {
        $em->remove($user);
        $em->flush();
        $users = $userRepository->findAll();
        return $this->render('user/list.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }
    public function user_list(
        UserDoctrineRepository $userRepository
    ): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/list.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }
    public function user_list_role(UserDoctrineRepository $userRepository, string $role): Response
    {
        $users = $userRepository->findBy(array('roles'=> $role));
        return $this->render('user/list.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }
    public function user_create(Request $request, UrlGeneratorInterface $urlGenerator, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, FormFactoryInterface $formFactory): Response
    {

        $userForm = new UserForm($em, $passwordHasher, $urlGenerator, $formFactory);
        $form = $userForm->createForm();
        $userForm->processForm($form, $request);
        //dd($resp);
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
        ]);
    }

    public function user_check(UserAccount $user_account) : Response
    {
        return $this->render('user/result.html.twig', [
            'controller_name' => 'UserController',
            'account' => $user_account,
        ]);
    }

}
