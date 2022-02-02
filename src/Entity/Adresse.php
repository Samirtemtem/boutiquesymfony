<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    private $IdClient;

    #[ORM\Column(type: 'string', length: 10)]
    private $CodePostale;

    #[ORM\Column(type: 'boolean')]
    private $ParDefaut;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdClient(): ?Client
    {
        return $this->IdClient;
    }

    public function setIdClient(?Client $IdClient): self
    {
        $this->IdClient = $IdClient;

        return $this;
    }

    public function getCodePostale(): ?string
    {
        return $this->CodePostale;
    }

    public function setCodePostale(string $CodePostale): self
    {
        $this->CodePostale = $CodePostale;

        return $this;
    }

    public function getParDefaut(): ?bool
    {
        return $this->ParDefaut;
    }

    public function setParDefaut(bool $ParDefaut): self
    {
        $this->ParDefaut = $ParDefaut;

        return $this;
    }
}
