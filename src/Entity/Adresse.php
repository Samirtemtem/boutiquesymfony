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
    #[ORM\Column(type: 'string', length: 10)]
    private $CodePostale;

    #[ORM\Column(type: 'boolean')]
    private $ParDefaut;

    #[ORM\Column(type: 'string', length: 255)]
    private $adresse;

    #[ORM\ManyToOne(targetEntity: Clientdebug::class, inversedBy: 'adresses')]
    private $IdClientDebug;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getIdClientDebug(): ?ClientDebug
    {
        return $this->IdClientDebug;
    }

    public function setIdClientDebug(?ClientDebug $IdClientDebug): self
    {
        $this->IdClientDebug = $IdClientDebug;

        return $this;
    }
}
