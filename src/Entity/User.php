<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getUsers"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getUsers"])]
    #[Assert\NotBlank(message: "Le prénom de l'utilisateur est obligatoire !")]
    #[Assert\Length(max: 50, maxMessage: "Le prénom de l'utilisateur ne peut pas dépasser {{ limit }} caractères !")]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getUsers"])]
    #[Assert\NotBlank(message: "Le nom de l'utilisateur est obligatoire !")]
    #[Assert\Length(max: 50, maxMessage: "Le nom de l'utilisateur ne peut pas dépasser {{ limit }} caractères !")]
    private ?string $first_name = null;

    #[ORM\Column(length: 100)]
    #[Groups(["getUsers"])]
    #[Assert\NotBlank(message: "L'adresse mail de l'utilisateur est obligatoire !")]
    #[Assert\Length(max: 100, maxMessage: "L'adresse mail de l'utilisateur ne peut pas dépasser {{ limit }} caractères !")]
    #[Assert\Email(message: "Le format de l'adresse mail {{ value }} n'est pas valide !")]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[Groups(["getUsers"])]
    private ?Client $client = null;

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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
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

    public function getClientId(): ?Client
    {
        return $this->client;
    }

    public function setClientId(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
