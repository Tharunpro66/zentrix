<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection; // If you add user relation later
use Doctrine\Common\Collections\Collection;    // If you add user relation later
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // For validation

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Company name cannot be blank.")]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $isActive = true; // Default to true

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    // If you plan to add relation to User entity (one company has many users)
    // #[ORM\OneToMany(mappedBy: 'company', targetEntity: User::class)]
    // private Collection $users;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        // $this->users = new ArrayCollection(); // if users relation is added
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isIsActive(): ?bool // Symfony forms might prefer isIsActive() for boolean getters
    {
        return $this->isActive;
    }

    // Or just isActive() - check what your form type prefers
    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // If you add users relation
    // /**
    //  * @return Collection<int, User>
    //  */
    // public function getUsers(): Collection
    // {
    //     return $this->users;
    // }

    // public function addUser(User $user): static
    // {
    //     if (!$this->users->contains($user)) {
    //         $this->users->add($user);
    //         $user->setCompany($this);
    //     }
    //     return $this;
    // }

    // public function removeUser(User $user): static
    // {
    //     if ($this->users->removeElement($user)) {
    //         // set the owning side to null (unless already changed)
    //         if ($user->getCompany() === $this) {
    //             $user->setCompany(null);
    //         }
    //     }
    //     return $this;
    // }

    public function __toString(): string // Useful for select dropdowns later
    {
        return $this->name ?? 'New Company';
    }
}