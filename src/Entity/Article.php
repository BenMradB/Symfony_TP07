<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Article name field must have a value .')]
    #[Assert\Length([], min: 5, max: 20, minMessage: "Article name must be at least {{ limit }}  characters .", maxMessage: "Article name at most {{ limit }} characters .")]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    #[Assert\NotBlank(message: 'Article price Field mus have a value .')]
    #[Assert\NotEqualTo(value: 0, message: 'Article price field cannot be null .')]
    #[Assert\Positive(message: 'Article price field must contain a positive value')]
    private ?string $price = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function __toString(): string
    {
        return "$this->name";
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }


}
