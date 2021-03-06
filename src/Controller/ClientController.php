<?php

namespace App\Controller;

use App\Entity\Clientdebug;
use App\Form\ClientdebugFormType;
use App\Form\RegistrationFormType;
use App\Repository\ClientdebugRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    protected ClientdebugRepository $ClientRepository;

    public function __construct(
        ClientdebugRepository $ClientRepository,
    ) {
        $this->ClientRepository = $ClientRepository;
    }

    #[Route('/admin/client', name: 'client')]

    public function index(): Response
    {
        $clients = $this->ClientRepository->findAll();
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients' => $clients
        ]);
    }
    #[Route('/admin/client/delete/{id}', name: 'deleteclient')]

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

    #[Route('/admin/client/modify/{id}', name: 'modifyclient')]

    public function modify(Request $request, ManagerRegistry $doctrine)
    {
        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];
        $client = $this->ClientRepository->find($id);
        $form = $this->createForm(RegistrationFormType::class, $client);
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

    #[Route('/admin/client/ajouter', name: 'ajouterclient')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $client = new Clientdebug();
        $form = $this->createForm(RegistrationFormType::class, $client);
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

            $this->addFlash("success", "Nouveau client " . $client->getNom() . " a ??t?? cr??e sous l'id " . $client->getid());
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
