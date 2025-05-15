<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity; // For unique email validation at entity level

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')] // Using backticks in case 'user' is a reserved keyword
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')] // More robust uniqueness
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)] // unique: true constraint at DB level
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)] // User's full name
    private ?string $name = null;

    #[ORM\Column(type: 'boolean')] // To activate/deactivate users
    private bool $isActive = true; // Default to active

    //#[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'users')] // Relationship to Company
    //#[ORM\JoinColumn(nullable: true)] // Super Admin might not belong to a company, or make it false if all users must. For MVP, nullable: true is fine for SA.
    //private ?Company $company = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Guarantee every user at least has ROLE_USER if no roles are set.
        // However, for Zentrix, users will explicitly be ROLE_SUPER_ADMIN or ROLE_COMPANY_USER.
        // So, if $roles is empty, we might not want to add ROLE_USER by default.
        // Let's adjust this: Super Admins will have ROLE_SUPER_ADMIN, Company Users will have ROLE_COMPANY_USER.
        // The default ROLE_USER is often for basic site members.
        if (empty($roles)) {
             $roles[] = 'ROLE_USER'; // Keep for now, can be removed if all users have explicit higher roles.
        }


        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    #public function getCompany(): ?Company
    #{
    #    return $this->company;
    #}

    #public function setCompany(?Company $company): static
    #{
    #    $this->company = $company;

    #    return $this;
    #}
}