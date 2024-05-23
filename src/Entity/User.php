<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

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
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getUsers"])]
    private ?string $first_name = null;

    #[ORM\Column(length: 100)]
    #[Groups(["getUsers"])]
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
