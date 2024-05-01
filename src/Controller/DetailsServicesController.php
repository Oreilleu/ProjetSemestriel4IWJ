<?php

namespace App\Controller;

use App\Entity\DetailsServices;
use App\Form\DetailsServicesType;
use App\Repository\DetailsServicesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/details/services')]
class DetailsServicesController extends AbstractController
{
    #[Route('/', name: 'app_details_services_index', methods: ['GET'])]
    public function index(DetailsServicesRepository $detailsServicesRepository): Response
    {
        return $this->render('details_services/index.html.twig', [
            'details_services' => $detailsServicesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_details_services_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $detailsService = new DetailsServices();
        $form = $this->createForm(DetailsServicesType::class, $detailsService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($detailsService);
            $entityManager->flush();

            return $this->redirectToRoute('app_details_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('details_services/new.html.twig', [
            'details_service' => $detailsService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_details_services_show', methods: ['GET'])]
    public function show(DetailsServices $detailsService): Response
    {
        return $this->render('details_services/show.html.twig', [
            'details_service' => $detailsService,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_details_services_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DetailsServices $detailsService, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DetailsServicesType::class, $detailsService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_details_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('details_services/edit.html.twig', [
            'details_service' => $detailsService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_details_services_delete', methods: ['POST'])]
    public function delete(Request $request, DetailsServices $detailsService, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detailsService->getId(), $request->request->get('_token'))) {
            $entityManager->remove($detailsService);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_details_services_index', [], Response::HTTP_SEE_OTHER);
    }
}
