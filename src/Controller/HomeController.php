<?php

namespace App\Controller;

use App\Service\HomeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{

    private $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    #[Route('/homepage', name: 'app_home')]
    public function index(): Response
    {
        $description = $this->homeService->getDescription();
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomeController',
            'description' => $description
        ]);
    }
}
