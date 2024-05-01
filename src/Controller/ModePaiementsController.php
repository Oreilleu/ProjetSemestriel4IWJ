<?php

namespace App\Controller;

use App\Entity\ModePaiements;
use App\Form\ModePaiementsType;
use App\Repository\ModePaiementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mode/paiements')]
class ModePaiementsController extends AbstractController
{
    #[Route('/', name: 'app_mode_paiements_index', methods: ['GET'])]
    public function index(ModePaiementsRepository $modePaiementsRepository): Response
    {
        return $this->render('mode_paiements/index.html.twig', [
            'mode_paiements' => $modePaiementsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mode_paiements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $modePaiement = new ModePaiements();
        $form = $this->createForm(ModePaiementsType::class, $modePaiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($modePaiement);
            $entityManager->flush();

            return $this->redirectToRoute('app_mode_paiements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mode_paiements/new.html.twig', [
            'mode_paiement' => $modePaiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mode_paiements_show', methods: ['GET'])]
    public function show(ModePaiements $modePaiement): Response
    {
        return $this->render('mode_paiements/show.html.twig', [
            'mode_paiement' => $modePaiement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mode_paiements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ModePaiements $modePaiement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModePaiementsType::class, $modePaiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mode_paiements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mode_paiements/edit.html.twig', [
            'mode_paiement' => $modePaiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mode_paiements_delete', methods: ['POST'])]
    public function delete(Request $request, ModePaiements $modePaiement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$modePaiement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($modePaiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mode_paiements_index', [], Response::HTTP_SEE_OTHER);
    }
}
