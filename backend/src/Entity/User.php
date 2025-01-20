<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 150)]
    private ?string $name = null;
    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(type: Types::STRING, length: 180)]
    #[Slug(fields: ['name'])]
    private ?string $slug = null;
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'user')]
    private ?Collection $posts = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;
    
    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\OneToMany(targetEntity: Like::class,  mappedBy: 'user')]
    private ?Collection $likes = null;

    #[ORM\OneToMany(targetEntity: Dislike::class,  mappedBy: 'user')]
    private ?Collection $dislikes = null;

    #[ORM\OneToMany(Comment::class, mappedBy:'user')]
    private ?Collection $comments = null;
    

    #[Groups('main')]
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

    #[Groups('main')]
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
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
        // $this->plainPassword = null;
    }

    #[Groups('main')]
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): static
    {
        $this->name = $name;

        return $this;
    }

    #[Groups('main')]
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPosts(): ?Collection
    {
        return $this->posts;
    }

    public function getSlug(): string|null
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): string|null
    {
        return $this->image;
    }

    public function setImage(?string $image)
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): string|null
    {
        return $this->description;
    }
 
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLikes(): array|Collection|null
    {
        return $this->likes;
    }

    public function setLikes($likes): static
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDislikes(): array|Collection|null
    {
        return $this->dislikes;
    }

    public function setDislikes($dislikes): static
    {
        $this->dislikes = $dislikes;

        return $this;
    }
}
