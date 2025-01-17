<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 150)]
    #[Slug(fields: ['title'])]
    private ?string $slug = null;

    #[ORM\OneToMany(targetEntity: Post::class,  mappedBy: 'category')]
    private ?Collection $posts = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?DateTimeInterface $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }


    public function getCreatedAt(): DateTimeInterface|null
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getPosts(): array|Collection|null
    {
        return $this->posts;
    }

    public function setPosts(ArrayCollection $posts)
    {
        $this->posts = $posts;

        return $this;
    }
}
