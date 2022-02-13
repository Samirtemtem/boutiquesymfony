<?php

namespace App\Entity;

use App\Repository\ProduitCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitCommandeRepository::class)]
class ProduitCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'ProduitCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    private $IdCommande;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'ProduitCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    private $IdProduit;

    #[ORM\Column(type: 'integer')]
    private $QteCommande;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 3)]
    private $PrixUnitaire;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 3)]
    private $PrixTotale;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCommande(): ?Commande
    {
        return $this->IdCommande;
    }

    public function setIdCommande(?Commande $IdCommande): self
    {
        $this->IdCommande = $IdCommande;

        return $this;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->IdProduit;
    }

    public function setIdProduit(?Produit $IdProduit): self
    {
        $this->IdProduit = $IdProduit;

        return $this;
    }

    public function getQteCommande(): ?int
    {
        return $this->QteCommande;
    }

    public function setQteCommande(int $QteCommande): self
    {
        $this->QteCommande = $QteCommande;

        return $this;
    }

    public function getPrixUnitaire(): ?string
    {
        return $this->PrixUnitaire;
    }

    public function setPrixUnitaire(string $PrixUnitaire): self
    {
        $this->PrixUnitaire = $PrixUnitaire;

        return $this;
    }

    public function getPrixTotale(): ?string
    {
        return $this->PrixTotale;
    }

    public function setPrixTotale(string $PrixTotale): self
    {
        $this->PrixTotale = $PrixTotale;

        return $this;
    }
    function __toString()
    {
        return ($this->getId());
    }
}
