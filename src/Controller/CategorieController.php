<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieFormType;
use App\Repository\ClientRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Client;

class CategorieController extends AbstractController
{
    protected CategorieRepository $CategorieRepository;
    protected ClientRepository $ClientRepository;
    public function __construct(
        CategorieRepository $CategorieRepository,
    ) {
        $this->CategorieRepository = $CategorieRepository;
    }

    #[Route('/categorie', name: 'categorie')]
    public function index(): Response
    {
        $categories = $this->CategorieRepository->findAll();
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categories' => $categories
        ]);
    }
    #[Route('/categorie/ajouter', name: 'ajoutercategorie')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieFormType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $exists = $this->CategorieRepository->findBy(array("Nom" => $categorie->getNom()));
            if ($exists != null) {
                return new Response($this->render(
                    'categorie/index.html.twig',
                    ["Error" => "Categorie deja existe",     "categorie_form" => $form->createView()]
                ));
            }
            $manager->persist($categorie);
            $manager->flush();
            $this->addFlash("success", "Nouvelle categorie" .   $categorie->getNom()  . " a été crée sous l'id " .   $categorie->getid());

            return new Response($this->render('categorie/modifier.html.twig', [
                'categorie_ajout' => "Nouvelle categorie " . $categorie->getNom() . " a été crée sous l'id " . $categorie->getid(),
                'categorie_form' => $form->createView()
            ]));
        }
        return new Response($this->render('categorie/modifier.html.twig', [
            'categorie_form' => $form->createView()
        ]));
    }

    #[Route('categorie/delete/{id}', name: 'deletecategorie')]
    public function delete(Request $request, ManagerRegistry $doctrine)
    {
        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];
        $categorie = $this->CategorieRepository->find($id);
        $entityManager = $doctrine->getManager();
        $entityManager->remove($categorie);
        $entityManager->flush();
        return ($this->index());
    }
    #[Route('categorie/modify/{id}', name: 'modifycategorie')]
    /*
 *
 * *
 * *NEEDS WORK
 * *
 * *
 */
    public function modify(Request $request, EntityManagerInterface $manager)
    {
        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];
        $categorie = $this->CategorieRepository->find($id);
        // $categorie->setNom();
        $form = $this->createForm(CategorieFormType::class, $categorie);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($request->request->get("categorie_form[Nom]"));
            $manager->persist($request);
            $manager->flush();
            $this->addFlash("succes", "Categorie avec ID" . $categorie->getNom() . "a été modifié");
            return new Response($this->render('categorie/index.html.twig', [
                'categorie_modifiee' => "Categorie avec ID" . $categorie->getNom() . "a été modifié"
            ]));
        }
        $form->handleRequest($request);
        return new Response($this->render('categorie/modifier.html.twig', [
            'categorie_form' => $form->createView(),
        ]));
    }
}
