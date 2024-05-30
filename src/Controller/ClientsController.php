<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\User;
use App\Form\ClientsType;
use App\Repository\ClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clients')]
class ClientsController extends AbstractController
{
    #[Route('/', name: 'app_clients_index')]
    public function index(ClientsRepository $clientsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {   

        $user = $this->getUser();

        if(!$user instanceof User) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();
        $entityManager->initializeObject($entreprise);

        $clients = $entreprise->getClients();

        if ($request->getMethod() === 'GET' && $request->query->get('searchkey')) {
            $clients = $clientsRepository->findByCritere($request->query->get('searchkey'));
        }

        return $this->render('clients/index.html.twig', [
            "clients" => $clients,
            "searchkey" => $request->query->get('searchkey') ? $request->query->get('searchkey') : null
        ]);
    }

    #[Route('/search', name: 'app_clients_search', methods: ['GET', 'POST'])]
    public function search(Request $request, ClientsRepository $clientsRepository): JsonResponse
    {
        $LesCriteres = [
            'id' => $request->query->get('id'),
            'nom' => $request->query->get('nom'),
            'prenom' => $request->query->get('prenom'),
        ];

        $clients = $clientsRepository->search($LesCriteres);

        return new JsonResponse($clients);
    }

    #[Route('/new', name: 'app_clients_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_register');
        }

        $client = new Clients();
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entreprise = $user->getIdEntreprise();
            $client->setIdEntreprise($entreprise);

            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_clients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clients/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clients_show', methods: ['GET'])]
    public function show(Clients $client): Response
    {
        return $this->render('clients/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_clients_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Clients $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_clients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clients/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clients_delete', methods: ['POST'])]
    public function delete(Request $request, Clients $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_clients_index', [], Response::HTTP_SEE_OTHER);
    }
}
