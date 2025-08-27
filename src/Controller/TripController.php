<?php

namespace App\Controller;

use App\Service\TripService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TripController extends AbstractController
{
    private TripService $tripService;

    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
    }

    #[Route('/trips/available', name: 'available_trips')]
    public function availableTrips(): Response
    {
        return $this->tripService->handleAvailableTripsPage();
    }

    #[Route('/trips/my-trips', name: 'my_trips')]
    public function myTrips(): Response
    {
        return $this->tripService->handleMyTripsPage($this->getUser());
    }

    #[Route('/trips/{id}/book', name: 'book_trip', methods: ['POST'])]
    public function bookTrip(int $id)
    {
        return $this->tripService->handleBookTrip($id);
    }

    #[Route('/trips/add', name: 'add_trip')]
    #[IsGranted('ROLE_ADMIN')]
    public function addTrip(Request $request): Response
    {
        return $this->tripService->handleAddTripPage($request);
    }

}
