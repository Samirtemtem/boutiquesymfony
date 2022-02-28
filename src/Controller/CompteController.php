<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseFormType;
use App\Repository\AdresseRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClientdebugRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompteController extends AbstractController
{
    protected ClientdebugRepository $ClientRepository;
    protected CommandeRepository $CommandeRepository;
    protected AdresseRepository $adresseRepository;

    public function __construct(
        ClientdebugRepository $ClientRepository,
        CommandeRepository $commandeRepository,
        AdresseRepository $adresseRepository
    ) {
        $this->CommandeRepository = $commandeRepository;
        $this->ClientRepository = $ClientRepository;
        $this->adresseRepository = $adresseRepository;
    }
    #[Route('/compte', name: 'compte')]
    public function index(): Response
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $client = $this->ClientRepository->findOneBy(array("id" => $this->getUser()->getId()));
            return $this->render('compte/index.html.twig', [
                'controller_name' => 'CompteController',
                'client' => $client
            ]);
        }
    }
    #[Route('/compte/commandes', name: 'comptecommandes')]
    public function commandes(): Response
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $client = $this->getUser();
            $commandes = $this->CommandeRepository->FindBy(array('IdClientDebug' => $client->getid()));
            return $this->render('compte/commandes.html.twig', [
                'controller_name' => 'CompteController',
                'client' => '$client',
                'commandes' => $commandes,
            ]);
        }
    }
    #[Route('/compte/adresses', name: 'compteadresses')]
    public function adresses(): Response
    {

        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $client = $this->getUser();
            $adresses = $this->adresseRepository->FindBy(array('IdClientDebug' => $client->getid()));
            return $this->render('compte/adresses.html.twig', [
                'controller_name' => 'CompteController',
                'client' => $client,
                'adresses' => $adresses
            ]);
        }
    }


    #[Route('/compte/adresses/ajouter', name: 'ajoutadresseclient')]
    public function adressesajouter(Request $request, EntityManagerInterface $manager): Response
    {
        $securityContext = $this->container->get('security.authorization_checker');
        $adresse = new Adresse();
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $client = $this->getUser();
            $form = $this->createForm(AdresseFormType::class, $adresse);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $adresse->setIdClientDebug($client);
                $manager->persist($adresse);
                $manager->flush();
            }
            $adresses = $this->adresseRepository->FindBy(array('IdClientDebug' => $client->getid()));
            return $this->render('compte/ajouteradresse.html.twig', [
                'controller_name' => 'CompteController',
                'client' => $client,
                'form' => $form->createView()
            ]);
        }
    }
}
