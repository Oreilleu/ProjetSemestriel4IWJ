<?php

namespace App\Controller;

use App\Entity\Interractions;
use App\Form\InterractionsType;
use App\Repository\InterractionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/interractions')]
class InterractionsController extends AbstractController
{
    #[Route('/', name: 'app_interractions_index', methods: ['GET'])]
    public function index(InterractionsRepository $interractionsRepository): Response
    {
        return $this->render('interractions/index.html.twig', [
            'interractions' => $interractionsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_interractions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $interraction = new Interractions();
        $form = $this->createForm(InterractionsType::class, $interraction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($interraction);
            $entityManager->flush();

            return $this->redirectToRoute('app_interractions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interractions/new.html.twig', [
            'interraction' => $interraction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_interractions_show', methods: ['GET'])]
    public function show(Interractions $interraction): Response
    {
        return $this->render('interractions/show.html.twig', [
            'interraction' => $interraction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_interractions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Interractions $interraction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InterractionsType::class, $interraction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_interractions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interractions/edit.html.twig', [
            'interraction' => $interraction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_interractions_delete', methods: ['POST'])]
    public function delete(Request $request, Interractions $interraction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$interraction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($interraction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_interractions_index', [], Response::HTTP_SEE_OTHER);
    }
}
