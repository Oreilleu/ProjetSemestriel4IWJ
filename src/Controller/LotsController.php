<?php

namespace App\Controller;

use App\Entity\Lots;
use App\Entity\User;
use App\Form\LotsType;
use App\Repository\ClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lots')]
class LotsController extends AbstractController
{
    #[Route('/', name: 'app_lots_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();

        $lots = $entreprise->getLots();

        return $this->render('lots/index.html.twig', [
            'lots' => $lots,
        ]);
    }

    #[Route('/by-client', name: 'app_lots_client', methods: ['GET'])]
    public function getLotsByClient(Request $request, ClientsRepository $clientsRepository): JsonResponse
    {
        $user = $this->getUser();
        
        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $clientId = $request->query->get('clientId');
        $userEntreprise = $user->getIdEntreprise();
        $responseArray = [];
        
        if ($clientId == 'all') {
            $lotsByEntreprise = $userEntreprise->getLots();
            foreach ($lotsByEntreprise as $lot) {
                $responseArray[] = ['id' => $lot->getId(), 'adresse' => $lot->getAdresse()];
            }
            return new JsonResponse($responseArray);
        }

        $client = $clientsRepository->find($clientId);

        if ($client->getIdEntreprise() !== $userEntreprise) {
            return new JsonResponse(['error' => 'Pas authorisé'], JsonResponse::HTTP_FORBIDDEN);
        }
            
        if (!$client) {
            return new JsonResponse(['error' => 'Client not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        
        if (!$clientId) {
            return new JsonResponse(['error' => 'Client ID is required'], JsonResponse::HTTP_BAD_REQUEST);
        }
                
        $lotsByClient = $client->getLots();
        foreach ($lotsByClient as $lot) {
            $responseArray[] = ['id' => $lot->getId(), 'adresse' => $lot->getAdresse()];
        }

        return new JsonResponse($responseArray);
    }

    #[Route('/new', name: 'app_lots_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();

        $lot = new Lots();
        $form = $this->createForm(LotsType::class, $lot, [
            'clients' => $entreprise->getClients(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $lot->setIdEntreprise($entreprise);

            $entityManager->persist($lot);
            $entityManager->flush();

            return $this->redirectToRoute('app_lots_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lots/new.html.twig', [
            'lot' => $lot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lots_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lots $lot, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();

        $lot = new Lots();
        $form = $this->createForm(LotsType::class, $lot, [
            'clients' => $entreprise->getClients(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lots_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lots/edit.html.twig', [
            'lot' => $lot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lots_delete', methods: ['POST'])]
    public function delete(Request $request, Lots $lot, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lot->getId(), $request->request->get('_token'))) {
            $entityManager->remove($lot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lots_index', [], Response::HTTP_SEE_OTHER);
    }
}
