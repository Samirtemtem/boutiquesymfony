<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 30)]
    private $Nom;

    #[ORM\OneToMany(mappedBy: 'Categorie', targetEntity: Produit::class)]
    private $Produits;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getProduits(): ?Produit
    {
        return $this->Produits;
    }

    public function setProduits(Produit $Produits): self
    {
        // set the owning side of the relation if necessary
        if ($Produits->getCategorie() !== $this) {
            $Produits->setCategorie($this);
        }

        $this->Produits = $Produits;

        return $this;
    }
    public function __toString()
    {
        return $this->getNom();
    }
}
