<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientFormType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    protected ClientRepository $ClientRepository;

    public function __construct(
        ClientRepository $ClientRepository,
    ) {
        $this->ClientRepository = $ClientRepository;
    }

    #[Route('/client', name: 'client')]

    public function index(): Response
    {
        $clients = $this->ClientRepository->findAll();
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients' => $clients
        ]);
    }
    #[Route('client/delete/{id}', name: 'deleteclient')]

    public function delete(Request $request, ManagerRegistry $doctrine)
    {
        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];
        $client = $this->ClientRepository->find($id);
        $entityManager = $doctrine->getManager();
        $entityManager->remove($client);
        $entityManager->flush();
        return ($this->index());
    }

    #[Route('client/modify/{id}', name: 'modifyclient')]

    public function modify(Request $request, ManagerRegistry $doctrine)
    {
        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];
        $client = $this->ClientRepository->find($id);
        $form = $this->createForm(ClientFormType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->flush();
            return
                $this->redirectToRoute('client');
        }
        return new Response($this->render('client/modifier.html.twig', [
            'client_form' => $form->createView(),
            'client_Nom' => $client->getNom()
        ]));
    }

    #[Route('/client/ajouter', name: 'ajouterclient')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientFormType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $exists = $this->ClientRepository->findBy(array("CIN" => $client->getCIN()));
            if ($exists != null) {
                return new Response($this->render(
                    'client/index.html.twig',
                    [
                        "Error" => "client deja existe",     "client_form" => $form->createView()
                    ]
                ));
            }
            $manager->persist($client);
            $manager->flush();
            $clients = $this->ClientRepository->findAll();

            $this->addFlash("success", "Nouveau client " . $client->getNom() . " a été crée sous l'id " . $client->getid());
            return new Response($this->render('client/index.html.twig', [
                'client_form' => $form->createView(),
                'clients' => $clients

            ]));
        }
        return new Response($this->render('client/ajouter.html.twig', [
            'client_form' => $form->createView()
        ]));
    }
}
