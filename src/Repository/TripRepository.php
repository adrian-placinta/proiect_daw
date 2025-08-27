<?php

namespace App\Repository;

use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function findUserTrips(int $userId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :userId')
            ->andWhere('t.isBooked = :booked')
            ->setParameter('userId', $userId)
            ->setParameter('booked', true)
            ->orderBy('t.departureDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAvailableTrips(): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.isAvailable = :available')
            ->setParameter('available', true)
            ->orderBy('t.departureDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

}