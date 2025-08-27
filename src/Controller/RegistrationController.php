<?php

namespace App\Controller;

use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private RegistrationService $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request): Response
    {
        $result = $this->registrationService->handleRegistration($request);

        if ($result['success']) {
            return $this->redirectToRoute('login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $result['formView']
        ]);
    }
}
