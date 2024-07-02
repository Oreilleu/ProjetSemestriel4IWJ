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
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/', name: 'app_entreprises_index', methods: ['GET'])]
    public function index(EntreprisesRepository $entreprisesRepository): Response
    {
        $entreprises = $entreprisesRepository->findAll();

        $entrepriseFiltered = array_filter($entreprises, function($entreprise) {
            // Assurez-vous que getUsers() retourne au moins un utilisateur avant de tenter d'accéder au premier élément
            if (!empty($entreprise->getUsers()) && count($entreprise->getUsers()) > 0){
                // Vérifiez si 'ROLE_ADMIN' est dans le tableau des rôles du premier utilisateur
                return !in_array('ROLE_ADMIN', $entreprise->getUsers()[0]->getRoles());
            }
            return true; // Inclure l'entreprise si aucun utilisateur n'est associé
        });


        return $this->render('entreprises/index.html.twig', [
            'entreprises' => $entrepriseFiltered,
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

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin-delete-entreprise/{id}', name: 'app_admin_entreprises_delete', methods: ['POST'])]
    public function adminDelete(Request $request, Entreprises $entreprise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->request->get('_token'))) {
            $users = $entreprise->getUsers();

            foreach ($users as $u) {
                $entityManager->remove($u);
                $entityManager->flush();
            }
            
            $entityManager->remove($entreprise);
            $entityManager->flush();
            $this->addFlash('success', 'Entreprise supprimée avec succès !');
        }

        return $this->redirectToRoute('app_entreprises_index', [], Response::HTTP_SEE_OTHER);
    }
}
