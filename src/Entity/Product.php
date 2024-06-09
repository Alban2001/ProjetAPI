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
     * Method getNumSerie
     *
     * @return string
     */
    public function getNumSerie(): ?string
    {
        return $this->numSerie;
    }

    /**
     * Method setNumSerie
     *
     * @param string $numSerie [explicite description]
     *
     * @return static
     */
    public function setNumSerie(string $numSerie): static
    {
        $this->numSerie = $numSerie;

        return $this;
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
     * @param string $name [explicite description]
     *
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method getModel
     *
     * @return string
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * Method setModel
     *
     * @param string $model [explicite description]
     *
     * @return static
     */
    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Method getMemoryRam
     *
     * @return int
     */
    public function getMemoryRam(): ?int
    {
        return $this->memoryRam;
    }

    /**
     * Method setMemoryRam
     *
     * @param int $memoryRam [explicite description]
     *
     * @return static
     */
    public function setMemoryRam(int $memoryRam): static
    {
        $this->memoryRam = $memoryRam;

        return $this;
    }

    /**
     * Method getMemoryStore
     *
     * @return int
     */
    public function getMemoryStore(): ?int
    {
        return $this->memoryStore;
    }

    /**
     * Method setMemoryStore
     *
     * @param int $memoryStore [explicite description]
     *
     * @return static
     */
    public function setMemoryStore(int $memoryStore): static
    {
        $this->memoryStore = $memoryStore;

        return $this;
    }

    /**
     * Method getTypeOS
     *
     * @return string
     */
    public function getTypeOS(): ?string
    {
        return $this->typeOS;
    }

    /**
     * Method setTypeOS
     *
     * @param string $typeOS [explicite description]
     *
     * @return static
     */
    public function setTypeOS(string $typeOS): static
    {
        $this->typeOS = $typeOS;

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
