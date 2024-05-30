<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $username;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\OneToMany(targetEntity: Prize::class, mappedBy: 'user', orphanRemoval: true)]
    private $prizes;

    public function __construct()
    {
        $this->prizes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        // Define the roles for the user
        // For example, return an array with a default role
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        // No salt needed for bcrypt hashing algorithm
        return null;
    }

    public function eraseCredentials(): void
    {
        // This method is not needed for most user providers
        // as the credentials are usually stored in a secure way
    }
    
    /**
     * @return Collection|Prize[]
     */
    public function getPrizes(): Collection
    {
        return $this->prizes;
    }

    public function addPrize(Prize $prize): self
    {
        if (!$this->prizes->contains($prize)) {
            $this->prizes[] = $prize;
            $prize->setUser($this);
        }

        return $this;
    }

    public function removePrize(Prize $prize): self
    {
        if ($this->prizes->contains($prize)) {
            $this->prizes->removeElement($prize);
            // set the owning side to null (unless already changed)
            if ($prize->getUser() === $this) {
                $prize->setUser(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }
}
