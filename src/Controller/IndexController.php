<?php

namespace App\Controller;

use App\Repository\AdresseRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use App\Repository\ClientdebugRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    protected CategorieRepository $CategorieRepository;
    protected ProduitRepository $ProduitRepository;
    protected AdresseRepository $adresseRepository;
    public function __construct(
        ProduitRepository $ProduitRepository,
        CategorieRepository $CategorieRepository,
        AdresseRepository $adresseRepository
    ) {
        $this->ProduitRepository = $ProduitRepository;
        $this->CategorieRepository = $CategorieRepository;
        $this->adresseRepository = $adresseRepository;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $categories = $this->CategorieRepository->findAll();
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'categories' => $categories
        ]);
    }
    #[Route('/categorie/{id}', name: 'afficherproduits')]
    public function afficher($id, ManagerRegistry $doctrine)
    {
        $categorie = $this->CategorieRepository->findby(array("id" => $id));
        $categorie = $categorie[0]->getId();
        $produits = $this->ProduitRepository->findBy(array("Categorie" => $categorie));
        return $this->render('index/produits.html.twig', [
            'categorie_name' => $categorie,
            'produits' => $produits
        ]);
    }

    #[Route('/produit/{id}', name: 'afficherproduit')]
    public function afficherproduit($id, ManagerRegistry $doctrine)
    {
        $produit = $this->ProduitRepository->findBy(array("Id" => $id));
        return $this->render('index/produits.html.twig', [
            'produit' => $produit,
        ]);
    }
    #[Route('/Consulterproduit/{id}', name: 'consulterproduit')]
    public function acheterproduit($id, ManagerRegistry $doctrine)
    {
        $produit = $this->ProduitRepository->findBy(array("id" => $id))[0];
        return $this->render('index/Consulterproduit.html.twig', [
            'produit' => $produit,
        ]);
    }
    #[Route('/achatproduit/{id}?qte={qte}', name: 'achatproduit')]
    public function achatproduit($id, $qte)
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $client = $this->getUser();
            $adresses = $this->adresseRepository->FindBy(array("IdClientDebug" => $client));
            if ($adresses) {
                $produit = $this->ProduitRepository->findBy(array("id" => $id))[0];
                return $this->render('index/Achatproduit.html.twig', [
                    'produit' => $produit,
                    'adresses' => $adresses
                ]);
            }
            return  $this->forward('App\Controller\CompteController::adresses');
        }
        return $this->forward('App\Controller\SecurityController::app_login');
    }
}
