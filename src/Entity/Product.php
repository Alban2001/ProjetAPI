<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $numSerie = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $model = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $memoryRam = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $memoryStore = null;

    #[ORM\Column(length: 25)]
    private ?string $typeOS = null;

    // Links
    private ?array $_links = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumSerie(): ?string
    {
        return $this->numSerie;
    }

    public function setNumSerie(string $numSerie): static
    {
        $this->numSerie = $numSerie;

        return $this;
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

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getMemoryRam(): ?int
    {
        return $this->memoryRam;
    }

    public function setMemoryRam(int $memoryRam): static
    {
        $this->memoryRam = $memoryRam;

        return $this;
    }

    public function getMemoryStore(): ?int
    {
        return $this->memoryStore;
    }

    public function setMemoryStore(int $memoryStore): static
    {
        $this->memoryStore = $memoryStore;

        return $this;
    }

    public function getTypeOS(): ?string
    {
        return $this->typeOS;
    }

    public function setTypeOS(string $typeOS): static
    {
        $this->typeOS = $typeOS;

        return $this;
    }

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
