<?php

namespace App\Controller;

use App\Entity\Lots;
use App\Form\LotsType;
use App\Repository\LotsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lots')]
class LotsController extends AbstractController
{
    #[Route('/', name: 'app_lots_index', methods: ['GET'])]
    public function index(LotsRepository $lotsRepository): Response
    {
        return $this->render('lots/index.html.twig', [
            'lots' => $lotsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lots_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lot = new Lots();
        $form = $this->createForm(LotsType::class, $lot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lot);
            $entityManager->flush();

            return $this->redirectToRoute('app_lots_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lots/new.html.twig', [
            'lot' => $lot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lots_show', methods: ['GET'])]
    public function show(Lots $lot): Response
    {
        return $this->render('lots/show.html.twig', [
            'lot' => $lot,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lots_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lots $lot, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LotsType::class, $lot);
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
