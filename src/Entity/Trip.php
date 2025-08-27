<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $destination = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $departureDate = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $availableSeats = 30;

    #[ORM\Column]
    private ?bool $isAvailable = true;

    #[ORM\Column]
    private ?bool $isBooked = false;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: UserTrip::class, orphanRemoval: true)]
    private Collection $userTrips;

    public function __construct()
    {
        $this->userTrips = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;
        return $this;
    }

    public function getDepartureDate(): ?\DateTimeImmutable
    {
        return $this->departureDate;
    }

    public function setDepartureDate(\DateTimeImmutable $departureDate): self
    {
        $this->departureDate = $departureDate;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getAvailableSeats(): ?int
    {
        return $this->availableSeats;
    }

    public function setAvailableSeats(int $availableSeats): self
    {
        $this->availableSeats = $availableSeats;
        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;
        return $this;
    }

    public function isBooked(): ?bool
    {
        return $this->isBooked;
    }

    public function setIsBooked(bool $isBooked): self
    {
        $this->isBooked = $isBooked;
        return $this;
    }

    /**
     * @return Collection<int, UserTrip>
     */
    public function getUserTrips(): Collection
    {
        return $this->userTrips;
    }

    public function addUserTrip(UserTrip $userTrip): self
    {
        if (!$this->userTrips->contains($userTrip)) {
            $this->userTrips->add($userTrip);
            $userTrip->setTrip($this);
        }
        return $this;
    }

    public function removeUserTrip(UserTrip $userTrip): self
    {
        if ($this->userTrips->removeElement($userTrip)) {
            if ($userTrip->getTrip() === $this) {
                $userTrip->setTrip(null);
            }
        }
        return $this;
    }
}