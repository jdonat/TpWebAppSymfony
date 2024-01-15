<?php

namespace App\Controller;

use App\Entity\UserAccount;
use App\Repository\UserDoctrineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{

    public function user_edit(UserDoctrineRepository $userRepository): Response
    {
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

    public function user_create(Request $request, EntityManagerInterface $em): Response
    {
        $user_account = new UserAccount();
        $form = $this->createFormBuilder($user_account)
            ->setAction($this->generateUrl('user_create'))
            ->setMethod('POST')
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
            ])
            ->add('submit_button', SubmitType::class)
            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user_account = $form->getData();
            $repository = $em->getRepository(UserAccount::class);
            $mail = $user_account->getEmail();
            if(!$repository->findOneBy(["email" => $mail]))
            {
                $em->persist($user_account);
                $em->flush();
                //dd($user_account);
                return $this->redirectToRoute('user_check');
            }
            else{
                return $this->redirectToRoute('user_create');
            }

        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
        ]);
    }

    public function user_check(UserAccount $user_account, Request $request) : Response
    {
        return $this->render('user/result.html.twig', [
            'controller_name' => 'UserController',
            'account' => $user_account,
        ]);
    }

}
