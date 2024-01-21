<?php

namespace App\Form;

use App\Entity\Roles;
use App\Entity\UserAccount;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserForm extends AbstractType
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private UrlGeneratorInterface $urlGenerator;
    private FormFactoryInterface $formFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UrlGeneratorInterface $urlGenerator,
        FormFactoryInterface $formFactory
    ) {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->urlGenerator = $urlGenerator;
        $this->formFactory = $formFactory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAction($this->urlGenerator->generate('user_create'))
            ->setMethod('POST')
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'choices'  => [ 'User' => Roles::ROLE_USER,
                    'Admin' => Roles::ROLE_ADMIN,
                    'Super Admin' => Roles::ROLE_SUPER_ADMIN ],
                'multiple' => true,
            ])
            ->add('submit_button', SubmitType::class);
    }

    public function processForm(FormInterface $form, Request $request): void
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userAccount = $form->getData();
            $plaintextPassword = $userAccount->getPassword();
            $repository = $this->entityManager->getRepository(UserAccount::class);
            $email = $userAccount->getEmail();

            if (!$repository->findOneBy(["email" => $email])) {
                $hashedPassword = $this->passwordHasher->hashPassword($userAccount, $plaintextPassword);
                $userAccount->setPassword($hashedPassword);
                $this->entityManager->persist($userAccount);
                $this->entityManager->flush();
            }
        }
    }
    public function createForm(): FormInterface
    {
        /*$formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new HttpFoundationExtension())
            ->getFormFactory();*/
            $user = new UserAccount();
        /*return $this->createForm(UserForm::class, $user);*/
        $formBuilder = $this->formFactory->createBuilder(UserForm::class, $user);
        $this->buildForm($formBuilder, []);
        return $formBuilder->getForm();
    }
}
