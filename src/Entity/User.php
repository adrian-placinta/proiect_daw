<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserTrip::class, orphanRemoval: true)]
    private Collection $userTrips;

    public function __construct()
    {
        $this->userTrips = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    /**
     * @return Collection<int, UserTrip>
     */
    public function getUserTrips(): Collection
    {
        return $this->userTrips;
    }

    public function addUserTrip(UserTrip $userTrip): static
    {
        if (!$this->userTrips->contains($userTrip)) {
            $this->userTrips->add($userTrip);
            $userTrip->setUser($this);
        }
        return $this;
    }

    public function removeUserTrip(UserTrip $userTrip): static
    {
        if ($this->userTrips->removeElement($userTrip)) {
            if ($userTrip->getUser() === $this) {
                $userTrip->setUser(null);
            }
        }
        return $this;
    }
}