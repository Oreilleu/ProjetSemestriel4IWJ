<?php

namespace App\Controller;

use App\Entity\CatServices;
use App\Form\CatServicesType;
use App\Repository\CatServicesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cat-services')]
class CatServicesController extends AbstractController
{
    #[Route('/', name: 'app_cat_services_index', methods: ['GET'])]
    public function index(CatServicesRepository $catServicesRepository): Response
    {
        return $this->render('cat_services/index.html.twig', [
            'cat_services' => $catServicesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cat_services_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $catService = new CatServices();
        $form = $this->createForm(CatServicesType::class, $catService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($catService);
            $entityManager->flush();

            return $this->redirectToRoute('app_cat_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cat_services/new.html.twig', [
            'cat_service' => $catService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cat_services_show', methods: ['GET'])]
    public function show(CatServices $catService): Response
    {
        return $this->render('cat_services/show.html.twig', [
            'cat_service' => $catService,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cat_services_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CatServices $catService, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CatServicesType::class, $catService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cat_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cat_services/edit.html.twig', [
            'cat_service' => $catService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cat_services_delete', methods: ['POST'])]
    public function delete(Request $request, CatServices $catService, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $catService->getId(), $request->request->get('_token'))) {
            $entityManager->remove($catService);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cat_services_index', [], Response::HTTP_SEE_OTHER);
    }
}
