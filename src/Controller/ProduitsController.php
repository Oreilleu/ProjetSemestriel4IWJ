<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/produits')]
class ProduitsController extends AbstractController
{
    #[Route('/', name: 'app_produits_index', methods: ['GET'])]
    public function index(ProduitsRepository $produitsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $ITEM_BY_PAGE = 2;
        $LENGTH_PRODUITS = count($produitsRepository->findAll());

        $query = $produitsRepository->findAll();

        $produits = $paginator->paginate(
            $query,     
            $request->query->getInt('page', 1), 
            $ITEM_BY_PAGE
        );

        $currentPage = $produits->getCurrentPageNumber();

        $empty = false;

        return $this->render('produits/index.html.twig', [
            'paginate_produits' => $produits,
            'number_page' => ceil($LENGTH_PRODUITS / $ITEM_BY_PAGE),
            'current_page' => $currentPage,
            'empty' => $empty
        ]);
    }

    #[Route('/new', name: 'app_produits_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produits/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/categorie/{idCategorie}', name: 'app_produits_categorie', methods: ['GET'])]
    public function categorie(ProduitsRepository $produitsRepository, PaginatorInterface $paginator, $idCategorie, Request $request): Response
    {

        $ITEM_BY_PAGE = 2;
        $LENGTH_PRODUITS = count($produitsRepository->findProduitsByCategorie($idCategorie));

        $query = $produitsRepository->findProduitsByCategorie($idCategorie);

        $produits = $paginator->paginate(
            $query,     
            $request->query->getInt('page', 1), 
            $ITEM_BY_PAGE
        );

        $currentPage = $produits->getCurrentPageNumber();

        $empty = false;

        if ($query == null) {
            $empty = true;
        }

        return $this->render('produits/index.html.twig', [
            'paginate_produits' => $produits,
            'number_page' => ceil($LENGTH_PRODUITS / $ITEM_BY_PAGE),
            'current_page' => $currentPage,
            'idCategorie' => $idCategorie,
            'empty' => $empty
        ]);
    }

    // #[Route('/{id}', name: 'app_produits_show', methods: ['GET'])]
    // public function show(Produits $produit): Response
    // {
    //     return $this->render('produits/show.html.twig', [
    //         'produit' => $produit,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_produits_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produits $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produits/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produits_delete', methods: ['POST'])]
    public function delete(Request $request, Produits $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
    }
}
