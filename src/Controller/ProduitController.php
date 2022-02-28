<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitFormType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    protected ProduitRepository $ProduitRepository;

    public function __construct(
        ProduitRepository $ProduitRepository,
    ) {
        $this->ProduitRepository = $ProduitRepository;
    }

    #[Route('admin/produit', name: 'produit')]
    public function index(): Response
    {
        $produits = $this->ProduitRepository->findAll();
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ClientController',
            'produits' => $produits
        ]);
    }

    #[Route('admin/produit/ajouter', name: 'ajouterproduit')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $produits = $this->ProduitRepository->findAll();
        $produit = new Produit();
        $form = $this->createForm(ProduitFormType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $exists = $this->ProduitRepository->findBy(array("Nom" => $produit->getNom()));
            if ($exists != null) {
                return new Response($this->render(
                    'produit/index.html.twig',
                    [
                        "Error" => "produit deja existe",     "produit_form" => $form->createView(),
                        "produits" => $produits
                    ]
                ));
            }
            $uploadedFile = $form['Nom_Image']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $produit->setNomImage($newFilename);
            $manager->persist($produit);
            $manager->flush();
            $this->addFlash("success", "Nouveau Produit " . $produit->getNom() . " a été crée sous l'id " . $produit->getid());
            return new Response($this->render('produit/index.html.twig', [
                'produit_form' => $form->createView(),
                'produits' => $produits

            ]));
        }
        return new Response($this->render('produit/ajouter.html.twig', [
            'produit_form' => $form->createView()
        ]));
    }


    #[Route('admin/produit/delete/{id}', name: 'deleteproduit')]

    public function delete(Request $request, ManagerRegistry $doctrine)
    {
        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];
        $produit = $this->ProduitRepository->find($id);
        $entityManager = $doctrine->getManager();
        $entityManager->remove($produit);
        $entityManager->flush();
        return ($this->index());
    }

    #[Route('/admin/produit/modify/{id}', name: 'modifyproduit')]

    public function modify(Produit $id, Request $request, ManagerRegistry $doctrine)
    {
        $routeParams = $request->attributes->get('_route_params');
        // $id = $routeParams['id'];
        $produit = $id;
        $form = $this->createForm(ProduitFormType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->flush();
            return
                $this->redirectToRoute('produit');
        }
        return new Response($this->render('produit/modifier.html.twig', [
            'produit_form' => $form->createView(),
            'produit_nom' => $produit->getNom()
        ]));
    }
}
