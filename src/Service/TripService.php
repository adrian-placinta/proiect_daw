<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserTrip;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class TripService extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private TripRepository $tripRepository;
    private Security $security;

    public function __construct(
        EntityManagerInterface $entityManager,
        TripRepository         $tripRepository,
        Security               $security
    )
    {
        $this->entityManager = $entityManager;
        $this->tripRepository = $tripRepository;
        $this->security = $security;
    }

    public function getUserTrips(User $user): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('ut', 't')                     // select UserTrip and Trip
            ->from(UserTrip::class, 'ut')
            ->join('ut.trip', 't')                  // join with Trip table
            ->where('ut.user = :user')
            ->setParameter('user', $user)
            ->orderBy('ut.bookingDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getAvailableTrips(): array
    {
        return $this->tripRepository->findAvailableTrips();
    }

    public function bookTrip(User $user, int $tripId): void
    {
        $trip = $this->tripRepository->find($tripId);

        if (!$trip) {
            throw new \Exception('Cursa nu a fost găsită.');
        }

        if (!$trip->isAvailable()) {
            throw new \Exception('Această cursă nu mai este disponibilă pentru rezervare.');
        }

        $userTrip = new UserTrip();
        $userTrip->setUser($user);
        $userTrip->setTrip($trip);

        $this->entityManager->persist($userTrip);
        $this->entityManager->flush();
    }


    public function handleAvailableTripsPage(): Response
    {
        $trips = $this->getAvailableTrips();

        return $this->render('trips/available.html.twig', [
            'trips' => $trips
        ]);
    }

    public function handleMyTripsPage(?User $user): Response
    {
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $trips = $this->getUserTrips($user);

        return $this->render('trips/my_trips.html.twig', [
            'trips' => $trips
        ]);
    }

    private function getCurrentUser(): ?User
    {
        return $this->security->getUser();
    }

    public function handleBookTrip(int $tripId): RedirectResponse
    {
        $user = $this->getCurrentUser();

        if (!$user) {
            throw new \Exception('User not logged in.');
        }

        try {
            $this->bookTrip($user, $tripId);
        } catch (\Exception $e) {
            throw $e;
        }

        return new RedirectResponse('/trips/my-trips');
    }
}
