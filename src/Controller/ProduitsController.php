<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Entity\User;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use App\Security\Voter\ProduitVoter;
use Doctrine\ORM\EntityManager;
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
    public function index(PaginatorInterface $paginator, Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        if (!$user instanceof User)  {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();
        $entityManager->initializeObject($entreprise);

        $produits = $entreprise->getProduits();

        $ITEM_BY_PAGE = 6;
        $LENGTH_PRODUITS = count($produits);

        $paginate_produits = $paginator->paginate(
            $produits,     
            $request->query->getInt('page', 1), 
            $ITEM_BY_PAGE
        );

        $currentPage = $paginate_produits->getCurrentPageNumber();

        return $this->render('produits/index.html.twig', [
            'paginate_produits' => $paginate_produits,
            'number_page' => ceil($LENGTH_PRODUITS / $ITEM_BY_PAGE),
            'current_page' => $currentPage,
        ]);
    }

    #[Route('/new', name: 'app_produits_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User)  {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();

        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit, [
            'categories' => $entreprise->getCategories(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['filePath']->getData();
            
            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads',
                    $fileName
                );
                $produit->setFilePath('/uploads/'.$fileName);
            }

            $entreprise = $user->getIdEntreprise();
            $produit->setIdEntreprise($entreprise);

            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Produit ajouté avec succès !');

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

        $ITEM_BY_PAGE = 6;
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

    #[Route('/{id}/edit', name: 'app_produits_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produits $product, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted(ProduitVoter::EDIT, $product);

        $user = $this->getUser();

        if (!$user instanceof User)  {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();

        $form = $this->createForm(ProduitsType::class, $product, [
            'categories' => $entreprise->getCategories(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['filePath']->getData();

            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads',
                    $fileName
                );
                $product->setFilePath('/uploads/'.$fileName);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Produit modifié avec succès !');

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produits/edit.html.twig', [
            'produit' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produits_delete', methods: ['POST'])]
    public function delete(Request $request, Produits $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Produit supprimé avec succès !');
        }

        return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
    }
}
