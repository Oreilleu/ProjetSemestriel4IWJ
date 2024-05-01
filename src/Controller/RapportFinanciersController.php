<?php

namespace App\Controller;

use App\Entity\RapportFinanciers;
use App\Form\RapportFinanciersType;
use App\Repository\RapportFinanciersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rapport/financiers')]
class RapportFinanciersController extends AbstractController
{
    #[Route('/', name: 'app_rapport_financiers_index', methods: ['GET'])]
    public function index(RapportFinanciersRepository $rapportFinanciersRepository): Response
    {
        return $this->render('rapport_financiers/index.html.twig', [
            'rapport_financiers' => $rapportFinanciersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rapport_financiers_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rapportFinancier = new RapportFinanciers();
        $form = $this->createForm(RapportFinanciersType::class, $rapportFinancier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rapportFinancier);
            $entityManager->flush();

            return $this->redirectToRoute('app_rapport_financiers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rapport_financiers/new.html.twig', [
            'rapport_financier' => $rapportFinancier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rapport_financiers_show', methods: ['GET'])]
    public function show(RapportFinanciers $rapportFinancier): Response
    {
        return $this->render('rapport_financiers/show.html.twig', [
            'rapport_financier' => $rapportFinancier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rapport_financiers_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RapportFinanciers $rapportFinancier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RapportFinanciersType::class, $rapportFinancier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rapport_financiers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rapport_financiers/edit.html.twig', [
            'rapport_financier' => $rapportFinancier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rapport_financiers_delete', methods: ['POST'])]
    public function delete(Request $request, RapportFinanciers $rapportFinancier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rapportFinancier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rapportFinancier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rapport_financiers_index', [], Response::HTTP_SEE_OTHER);
    }
}
