<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 200)]
    private $Nom;

    #[ORM\Column(type: 'string', length: 100)]
    private $DescCourt;

    #[ORM\Column(type: 'string', length: 255)]
    private $DescLong;

    #[ORM\ManyToOne(inversedBy: 'Produits', targetEntity: Categorie::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $Categorie;

    #[ORM\OneToMany(mappedBy: 'IdProduit', targetEntity: ProduitCommande::class)]
    private $ProduitCommandes;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private $Prix;

    #[ORM\Column(type: 'string', length: 255)]
    private $Nom_Image;

    public function __construct()
    {
        $this->ProduitCommandes = new ArrayCollection();
    }

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

    public function getDescCourt(): ?string
    {
        return $this->DescCourt;
    }

    public function setDescCourt(string $DescCourt): self
    {
        $this->DescCourt = $DescCourt;

        return $this;
    }

    public function getDescLong(): ?string
    {
        return $this->DescLong;
    }

    public function setDescLong(string $DescLong): self
    {
        $this->DescLong = $DescLong;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(Categorie $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    /**
     * @return Collection|ProduitCommande[]
     */
    public function getProduitCommandes(): Collection
    {
        return $this->ProduitCommandes;
    }

    public function addProduitCommande(ProduitCommande $produitCommande): self
    {
        if (!$this->ProduitCommandes->contains($produitCommande)) {
            $this->ProduitCommandes[] = $produitCommande;
            $produitCommande->setIdProduit($this);
        }

        return $this;
    }

    public function removeProduitCommande(ProduitCommande $produitCommande): self
    {
        if ($this->ProduitCommandes->removeElement($produitCommande)) {
            // set the owning side to null (unless already changed)
            if ($produitCommande->getIdProduit() === $this) {
                $produitCommande->setIdProduit(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->Prix;
    }

    public function setPrix(string $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getNomImage(): ?string
    {
        return $this->Nom_Image;
    }

    public function setNomImage(string $Nom_Image): self
    {
        $this->Nom_Image = $Nom_Image;

        return $this;
    }
}
