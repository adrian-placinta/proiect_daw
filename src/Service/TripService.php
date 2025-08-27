<?php

namespace App\Service;

use App\Entity\Trip;
use App\Entity\User;
use App\Entity\UserTrip;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
            ->select('ut', 't')
            ->from(UserTrip::class, 'ut')
            ->join('ut.trip', 't')
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

        if ($trip->getAvailableSeats() <= 0) {
            throw new \Exception('Nu mai sunt locuri disponibile pentru această cursă.');
        }

        $trip->setAvailableSeats($trip->getAvailableSeats() - 1);

        if ($trip->getAvailableSeats() === 0) {
            $trip->setIsAvailable(false);
        }

        $userTrip = new UserTrip();
        $userTrip->setUser($user);
        $userTrip->setTrip($trip);

        $this->entityManager->persist($userTrip);
        $this->entityManager->persist($trip);
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

    public function handleAddTripPage(Request $request): Response
    {
        $trip = new Trip();

        $form = $this->createFormBuilder($trip)
            ->add('destination', TextType::class, [
                'label' => 'Destinație',
            ])
            ->add('departureDate', DateTimeType::class, [
                'label' => 'Data plecării',
                'widget' => 'single_text',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Preț',
            ])
            ->add('availableSeats', IntegerType::class, [
                'label' => 'Locuri disponibile',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($trip);
            $this->entityManager->flush();

            $this->addFlash('success', 'Cursa a fost adăugată cu succes!');
            return $this->redirectToRoute('available_trips');
        }

        return $this->render('trips/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
