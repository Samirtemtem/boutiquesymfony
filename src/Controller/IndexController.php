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
}