<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

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

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'users')]
    #[Groups(["getUsers"])]
    private ?Client $client = null;

    // Links
    #[Groups(["getUsers"])]
    private ?array $_links = null;

    /**
     * Method getId
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Method getName
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Method setName
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method getFirstName
     *
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * Method setFirstName
     *
     * @param string $first_name [explicite description]
     *
     * @return static
     */
    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Method getEmail
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Method setEmail
     *
     * @param string $email [explicite description]
     *
     * @return static
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method getClientId
     *
     * @return Client
     */
    public function getClientId(): ?Client
    {
        return $this->client;
    }

    /**
     * Method setClientId
     *
     * @param ?Client $client [explicite description]
     *
     * @return static
     */
    public function setClientId(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Method getLinks
     *
     * @return array
     */
    public function getLinks(): array
    {
        return $this->_links;
    }

    /**
     * @param list<string> $_links
     */
    public function setLinks(array $_links): static
    {
        $this->_links = $_links;

        return $this;
    }
}
