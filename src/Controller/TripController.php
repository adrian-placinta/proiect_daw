<?php

namespace App\Controller;

use App\Service\TripService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TripController extends AbstractController
{
    private $tripService; 

    public function __construct(TripService $tripService)
    {
       $this->tripService = $tripService;
    }

    #[Route('/curse', name: 'app_curse')]
    public function index(): Response
    {
        return $this->render('trips/index.html.twig', [
            'question' => 'De ce serviciile noaste?',
            'page_title' => 'Curse',
            'features' => $this->tripService->getFeatures(),
            'trips' => $this->tripService->getTrips()
        ]);
    }
}
