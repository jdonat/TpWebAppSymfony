<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{

    public function user_form(): Response
    {
        $user = new UserEntity();
        $form = $this->createFormBuilder($user)
            ->setAction($this->generateUrl('create_user'))
            ->setMethod('POST')
            ->add('first_name', TextType::class, ['required' => true])
            ->add('last_name', TextType::class, ['required' => true])
            ->add('email', EmailType::class, ['required' => true])
            ->add('password', PasswordType::class, ['required' => true])
            ->add('roles', ChoiceType::class, ['choices' => [
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN',
                'Super Admin' => 'ROLE_SUPER_ADMIN',
            ],
                'multiple' => true,
                'required' => true])
            ->add('submit', SubmitType::class);
        $form = $form->getForm();



        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
        ]);
    }
    public function new_user(Request $request) : Response
    {
        $user = new UserEntity();
        $form = $this->createFormBuilder($user)
            ->setAction($this->generateUrl('create_user'))
            ->setMethod('POST')
            ->add('first_name', TextType::class, ['required' => true])
            ->add('last_name', TextType::class, ['required' => true])
            ->add('email', EmailType::class, ['required' => true])
            ->add('password', PasswordType::class, ['required' => true])
            ->add('roles', ChoiceType::class, ['choices' => [
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN',
                'Super Admin' => 'ROLE_SUPER_ADMIN',
            ],
                'multiple' => true,
                'required' => true])
            ->add('submit', SubmitType::class);
        $form = $form->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($form->isValid()) {
                // data is an array with "name", "email", and "message" keys
                $data = $form->getData();

                return $this->render('user/result.html.twig', [
                    'controller_name' => 'UserController',
                    'firstname' => $data['first_name'],
                    'lastname' => $data['last_name'],
                    'email' => $data['email'],
                    'roles' => $data['roles'],
                ]);
            }
            else
            {
                return $this->render('user/result.html.twig', [
                    'message' => 'Erreur de validation du formulaire',
                ]);
            }
        }

    }
}
