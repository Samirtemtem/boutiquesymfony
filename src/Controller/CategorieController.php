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
    public function __construct(
        CategorieRepository $CategorieRepository,
    ) {
        $this->CategorieRepository = $CategorieRepository;
    }

    #[Route('/admin/categorie', name: 'categorie')]
    public function index(): Response
    {
        $categories = $this->CategorieRepository->findAll();
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categories' => $categories
        ]);
    }
    #[Route('/admin/categorie/ajouter', name: 'ajoutercategorie')]
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
            return ($this->redirectToRoute("categorie"));
        }
        return new Response($this->render('categorie/modifier.html.twig', [
            'categorie_form' => $form->createView()
        ]));
    }

    #[Route('/admin/categorie/delete/{id}', name: 'deletecategorie')]
    public function delete(Request $request, ManagerRegistry $doctrine)
    {
        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];
        $categorie = $this->CategorieRepository->find($id);

        $entityManager = $doctrine->getManager();
        $entityManager->remove($categorie);
        $entityManager->flush();
        return ($this->redirectToRoute("categorie"));
    }
    #[Route('/admin/categorie/modify/{id}', name: 'modifycategorie')]
    /*
 *
 * *
 * *NEEDS WORK
 * *
 * *
 */

    public function modify(Request $request, ManagerRegistry $doctrine)
    {
        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];
        $categorie = $this->CategorieRepository->find($id);
        $form = $this->createForm(CategorieFormType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->flush();
            return
                $this->redirectToRoute('categorie');
        }
        return new Response($this->render('categorie/modifier.html.twig', [
            'categorie_form' => $form->createView(),
            'categorie_nom' => $categorie->getNom()
        ]));
    }
}
