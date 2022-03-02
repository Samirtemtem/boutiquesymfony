<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\ProduitCommande;
use App\Form\CommandeFormType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitCommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    protected ProduitCommandeRepository $produitsCommande;
    protected CommandeRepository $CommandeRepository;

    public function __construct(
        ProduitCommandeRepository $produitsCommande,
        CommandeRepository $commandeRepository,
    ) {
        $this->produitsCommande = $produitsCommande;
        $this->CommandeRepository = $commandeRepository;
    }
    #[Route('/admin/commande', name: 'commande')]
    public function index(): Response
    {
        $commandes = $this->CommandeRepository->findAll();
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'commandes' => $commandes
        ]);
    }



    #[Route('/admin/commande/ajouter', name: 'ajoutercommande')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $commandes = $this->CommandeRepository->findAll();
        $commande = new Commande();
        $form = $this->createForm(CommandeFormType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $exists = $this->CommandeRepository->findBy(array("id" => $commande->getId()));
            if ($exists != null) {
                return new Response($this->render(
                    'commande/index.html.twig',
                    [
                        "Error" => "commande deja existe",     "commande_form" => $form->createView(),
                        "commande" => $commandes
                    ]
                ));
            }
            $manager->persist($commande);
            $manager->flush();
            $this->addFlash("success", "Nouvelle Commande  a été crée sous l'id " . $commande->getid());
            return new Response($this->render('commande/index.html.twig', [
                'commande_form' => $form->createView(),
                'commandes' => $commandes

            ]));
        }
        return new Response($this->render('commande/ajouter.html.twig', [
            'commande_form' => $form->createView()
        ]));
    }


    #[Route('/admin/commande/delete/{id}', name: 'deletecommande')]

    public function delete(Request $request, ManagerRegistry $doctrine)
    {
        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];
        $commande = $this->CommandeRepository->find($id);
        $produitCommande = $this->produitsCommande->findby(array("IdCommande" => $commande))[0];
        $entityManager = $doctrine->getManager();
        $entityManager->remove($produitCommande);
        $entityManager->remove($commande);
        $entityManager->flush();
        return ($this->index());
    }

    #[Route('/admin/commande/modify/{id}', name: 'modifycommande')]

    public function modify(Request $request, ManagerRegistry $doctrine)
    {
        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];
        $commande = $this->CommandeRepository->find($id);
        $form = $this->createForm(CommandeFormType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->flush();
            return
                $this->redirectToRoute('commande');
        }
        return new Response($this->render('commande/modifier.html.twig', [
            'commande_form' => $form->createView(),
            'commande_Id' => $commande->getId()
        ]));
    }
}
