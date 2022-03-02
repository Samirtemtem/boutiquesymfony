<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseFormType;
use App\Repository\AdresseRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClientdebugRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProduitCommandeRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

class CompteController extends AbstractController
{
    protected ProduitRepository $Produits;
    protected ProduitCommandeRepository $produitCommandes;
    protected ClientdebugRepository $ClientRepository;
    protected CommandeRepository $CommandeRepository;
    protected AdresseRepository $adresseRepository;

    public function __construct(
        ProduitRepository $produitRepository,
        ProduitCommandeRepository $ProduitsCommandes,
        ClientdebugRepository $ClientRepository,
        CommandeRepository $commandeRepository,
        AdresseRepository $adresseRepository
    ) {
        $this->ProduitRepository = $produitRepository;
        $this->ProduitCommandes = $ProduitsCommandes;
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
            $commandes = $this->CommandeRepository->findAll(array("IdClient" => $client));
            $adresses = $this->adresseRepository->findAll(array("IdClient" => $client));

            return $this->render('compte/index.html.twig', [
                'controller_name' => 'CompteController',
                'client' => $client,
                'commandes' => $commandes,
                'adresses' => $adresses
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


    #[Route('/compte/commande/{id}', name: 'comptecommande')]
    public function commande($id): Response
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $client = $this->getUser();
            $commandes = $this->CommandeRepository->Find($id);
            $produitCommande = $this->ProduitCommandes->findby(array("IdCommande" => $commandes))[0];
            $produit = $this->ProduitRepository->findby(array("id" => $produitCommande->getIdProduit()->getid()))[0];
            return $this->render('compte/commande_voir.html.twig', [
                'controller_name' => 'CompteController',
                'client' => $client,
                'produit' => $produit,
                'commande' => $commandes
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
                return  $this->forward('App\Controller\CompteController::adresses');
            }
            $adresses = $this->adresseRepository->FindBy(array('IdClientDebug' => $client->getid()));
            return $this->render('compte/ajouteradresse.html.twig', [
                'controller_name' => 'CompteController',
                'client' => $client,
                'form' => $form->createView()
            ]);
        }
    }
    #[Route('/compte/adresses/supprimer/{id}', name: 'deleteadresse')]
    public function adressesupprimer($id, ManagerRegistry $doctrine)
    {
        $adresse = $this->adresseRepository->find($id);
        $entityManager = $doctrine->getManager();
        $entityManager->remove($adresse);
        $entityManager->flush();
        return ($this->adresses());
    }

    #[Route('/compte/commandes/supprimer/{id}', name: 'supprimercommande')]
    public function commandesupprimer($id, ManagerRegistry $doctrine)
    {
        $commande = $this->CommandeRepository->find($id);
        $produitCommande = $this->ProduitCommandes->findby(array("IdCommande" => $commande))[0];
        $entityManager = $doctrine->getManager();
        $entityManager->remove($produitCommande);
        $entityManager->remove($commande);
        $entityManager->flush();
        return ($this->commandes());
    }
}
