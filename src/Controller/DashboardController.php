<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ClientdebugRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    protected ClientdebugRepository $ClientRepository;
    protected ProduitRepository $ProduitRepository;
    protected CommandeRepository $CommandeRepository;
    protected CategorieRepository $CategorieRepository;

    public function __construct(
        ClientdebugRepository $ClientRepository,
        ProduitRepository $ProduitRepository,
        CommandeRepository $CommandeRepository,
        CategorieRepository $categorieRepository
    ) {
        $this->ProduitRepository = $ProduitRepository;
        $this->CommandeRepository = $CommandeRepository;
        $this->ClientRepository = $ClientRepository;
        $this->CategorieRepository = $categorieRepository;
    }
    #[Route('/admin', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'countClients' => $this->ClientRepository->findAll(),
            'countProduits' => $this->ProduitRepository->findAll(),
            'countCommandes' => $this->CommandeRepository->findAll(),
            'countCategories' => $this->CategorieRepository->findAll(),
            "Commandesnonconf" => $this->CommandeRepository->findby(array("Etat" => false))
        ]);
    }
}
