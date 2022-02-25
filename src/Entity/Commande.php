<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'Commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private $IdClient;

    #[ORM\Column(type: 'date')]
    private $Date;

    #[ORM\Column(type: 'boolean')]
    private $Etat;

    #[ORM\OneToMany(mappedBy: 'IdCommande', targetEntity: ProduitCommande::class)]
    private $ProduitCommandes;

    #[ORM\ManyToOne(targetEntity: Clientdebug::class, inversedBy: 'commandes')]
    private $IdClientDebug;

    public function __construct()
    {
        $this->ProduitCommandes = new ArrayCollection();
    }

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->Etat;
    }

    public function setEtat(bool $Etat): self
    {
        $this->Etat = $Etat;

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
            $produitCommande->setIdCommande($this);
        }

        return $this;
    }

    public function removeProduitCommande(ProduitCommande $produitCommande): self
    {
        if ($this->ProduitCommandes->removeElement($produitCommande)) {
            // set the owning side to null (unless already changed)
            if ($produitCommande->getIdCommande() === $this) {
                $produitCommande->setIdCommande(null);
            }
        }

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
