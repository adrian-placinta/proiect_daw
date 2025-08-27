<?php

namespace App\Service;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationService
{
    private FormFactoryInterface $formFactory;
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;

    public function __construct(
        FormFactoryInterface $formFactory,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ) {
        $this->formFactory = $formFactory;
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    public function handleRegistration(Request $request): array
    {
        $user = new User();
        $form = $this->formFactory->create(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();

            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $plainPassword)
            );
            $user->setRoles(['ROLE_USER']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return ['success' => true];
        }

        return [
            'success' => false,
            'formView' => $form->createView()
        ];
    }
}
