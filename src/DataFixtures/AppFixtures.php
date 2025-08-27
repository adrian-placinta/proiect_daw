<?php

namespace App\DataFixtures;

use App\Entity\Trip;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create admin user
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($admin, 'admin');
        $admin->setPassword($password);
        $manager->persist($admin);

        // Create test trips
        $destinations = [
            'București' => 100,
            'Iași' => 80,
            'Cluj' => 120,
            'Timișoara' => 150,
            'Constanța' => 90,
            'Brașov' => 70,
            'Sibiu' => 85,
            'Oradea' => 110
        ];

        $currentDate = new \DateTimeImmutable();

        foreach ($destinations as $city => $price) {
            // Morning trip
            $morningTrip = new Trip();
            $morningTrip->setDestination($city);
            $morningTrip->setDepartureDate($currentDate->modify('+1 day')->setTime(8, 0));
            $morningTrip->setPrice($price);
            $morningTrip->setAvailableSeats(30);
            $morningTrip->setIsAvailable(true);
            $manager->persist($morningTrip);

            // Evening trip
            $eveningTrip = new Trip();
            $eveningTrip->setDestination($city);
            $eveningTrip->setDepartureDate($currentDate->modify('+1 day')->setTime(16, 0));
            $eveningTrip->setPrice($price * 0.9); // 10% discount for evening trips
            $eveningTrip->setAvailableSeats(30);
            $eveningTrip->setIsAvailable(true);
            $manager->persist($eveningTrip);
        }

        $manager->flush();
    }
}