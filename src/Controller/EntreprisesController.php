<?php

namespace App\Controller;

use App\Entity\Entreprises;
use App\Form\EntreprisesType;
use App\Repository\EntreprisesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/entreprises')]
class EntreprisesController extends AbstractController
{
    #[IsGranted('ROLE_COMPTABLE')]
    #[Route('/', name: 'app_entreprises_index', methods: ['GET'])]
    public function index(EntreprisesRepository $entreprisesRepository): Response
    {
        $entreprises = $entreprisesRepository->findAll();

        return $this->render('entreprises/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    #[Route('/new', name: 'app_entreprises_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entreprise = new Entreprises();
        $form = $this->createForm(EntreprisesType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entreprise);
            $entityManager->flush();
            $this->addFlash('success', 'Entreprise ajoutée avec succès !');

            return $this->redirectToRoute('app_entreprises_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entreprises/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entreprises_show', methods: ['GET'])]
    public function show(Entreprises $entreprise): Response
    {
        return $this->render('entreprises/show.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_entreprises_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entreprises $entreprise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EntreprisesType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Entreprise modifiée avec succès !');

            return $this->redirectToRoute('app_entreprises_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entreprises/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entreprises_delete', methods: ['POST'])]
    public function delete(Request $request, Entreprises $entreprise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->request->get('_token'))) {
            $entityManager->remove($entreprise);
            $entityManager->flush();
            $this->addFlash('success', 'Entreprise supprimée avec succès !');
        }

        return $this->redirectToRoute('app_entreprises_index', [], Response::HTTP_SEE_OTHER);
    }
}
