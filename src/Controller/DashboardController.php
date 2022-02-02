<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    protected ClientRepository $ClientRepository;
    protected ProduitRepository $ProduitRepository;
    protected CommandeRepository $CommandeRepository;

    public function __construct(
        ClientRepository $ClientRepository,
        ProduitRepository $ProduitRepository,
        CommandeRepository $CommandeRepository,
    ) {
        $this->ProduitRepository = $ProduitRepository;
        $this->CommandeRepository = $CommandeRepository;
        $this->ClientRepository = $ClientRepository;
    }
    #[Route('/dashboard', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'countClients' => $this->ClientRepository->findAll(),
            'countProduits' => $this->ProduitRepository->findAll(),
            'countCommandes' => $this->CommandeRepository->findAll()
        ]);
    }
}
