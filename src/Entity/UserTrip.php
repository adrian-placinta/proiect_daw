<?php

namespace App\Entity;

use App\Repository\UserTripRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserTripRepository::class)]
class UserTrip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userTrips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userTrips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trip $trip = null;

    #[ORM\Column(length: 255)]
    private ?string $qrCode = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $bookingDate = null;

    public function __construct()
    {
        $this->qrCode = md5(uniqid(time(), true));
        $this->bookingDate = new \DateTimeImmutable();
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function getBookingDate(): ?\DateTimeImmutable
    {
        return $this->bookingDate;
    }

    // Setters
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function setTrip(Trip $trip): self
    {
        $this->trip = $trip;
        return $this;
    }

    public function setQrCode(string $qrCode): self
    {
        $this->qrCode = $qrCode;
        return $this;
    }

    public function setBookingDate(\DateTimeImmutable $bookingDate): self
    {
        $this->bookingDate = $bookingDate;
        return $this;
    }
}
