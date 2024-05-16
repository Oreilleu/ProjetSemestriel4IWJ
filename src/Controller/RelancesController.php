<?php

namespace App\Controller;

use App\Entity\Relances;
use App\Form\RelancesType;
use App\Repository\RelancesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/relances')]
class RelancesController extends AbstractController
{
    #[Route('/', name: 'app_relances_index', methods: ['GET'])]
    public function index(RelancesRepository $relancesRepository): Response
    {
        return $this->render('relances/index.html.twig', [
            'relances' => $relancesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_relances_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $relance = new Relances();
        $form = $this->createForm(RelancesType::class, $relance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($relance);
            $entityManager->flush();

            return $this->redirectToRoute('app_relances_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('relances/new.html.twig', [
            'relance' => $relance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_relances_show', methods: ['GET'])]
    public function show(Relances $relance): Response
    {
        return $this->render('relances/show.html.twig', [
            'relance' => $relance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_relances_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Relances $relance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RelancesType::class, $relance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_relances_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('relances/edit.html.twig', [
            'relance' => $relance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_relances_delete', methods: ['POST'])]
    public function delete(Request $request, Relances $relance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$relance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($relance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_relances_index', [], Response::HTTP_SEE_OTHER);
    }
}
