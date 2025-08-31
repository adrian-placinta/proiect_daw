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
    
        $this->seedAdminUser($manager);

       
        $this->seedTrips($manager);

        $manager->flush(); 
    }

    private function seedAdminUser(ObjectManager $manager): void
    {
        $userRepo = $manager->getRepository(User::class);
        if ($userRepo->findOneBy(['username' => 'admin'])) {
            return;
        }
        
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);
    }

    private function seedTrips(ObjectManager $manager): void
    {
        $tripRepo = $manager->getRepository(Trip::class);
        if ($tripRepo->count([]) > 0) {
            return;
        }

        $destinations = [
            ['București - Iași', 100],
            ['Cluj - Timișoara', 120],
            ['Constanța - Brașov', 90],
            ['Oradea - Sibiu', 110],
            ['Galați - Bacău', 85],
        ];

        $today = new \DateTimeImmutable();

        foreach ($destinations as [$city, $price]) {
            $this->createTrip($manager, $city, $today->modify('+1 day')->setTime(8, 0), $price);
            $this->createTrip($manager, $city, $today->modify('+1 day')->setTime(18, 0), $price * 0.9);
        }
    }

    private function createTrip(ObjectManager $manager, string $destination, \DateTimeImmutable $departureDate, float $price): void
    {
        $trip = new Trip();
        $trip->setDestination($destination)
             ->setDepartureDate($departureDate)
             ->setPrice($price)
             ->setAvailableSeats(30)
             ->setIsAvailable(true)
             ->setIsBooked(false);

        $manager->persist($trip);
    }
}