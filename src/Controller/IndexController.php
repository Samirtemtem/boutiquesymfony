<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    protected CategorieRepository $CategorieRepository;
    protected ProduitRepository $ProduitRepository;
    public function __construct(
        ProduitRepository $ProduitRepository,
        CategorieRepository $CategorieRepository,
    ) {
        $this->ProduitRepository = $ProduitRepository;
        $this->CategorieRepository = $CategorieRepository;
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
    #[Route('/Consulterproduit/{id}', name: 'acheterproduit')]
    public function acheterproduit($id, ManagerRegistry $doctrine)
    {
        $produit = $this->ProduitRepository->findBy(array("id" => $id))[0];
        return $this->render('index/Consulterproduit.html.twig', [
            'produit' => $produit,
        ]);
    }
    #[Route('/achatproduit/{id}', name: 'achatproduit')]
    public function achatproduit($id, ManagerRegistry $doctrine)
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $produit = $this->ProduitRepository->findBy(array("id" => $id))[0];
            return $this->render('index/Achatproduit.html.twig', [
                'produit' => $produit,
            ]);
        }
        dd("redirect to login user");
    }
}
