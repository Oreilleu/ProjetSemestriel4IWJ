<?php

namespace App\Controller;

use App\Entity\CategoriesProduits;
use App\Form\CategoriesProduitsType;
use App\Repository\CategoriesProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/categories')]
class CategoriesProduitsController extends AbstractController
{
    #[Route('/', name: 'app_categories_index', methods: ['GET'])]
    public function index(CategoriesProduitsRepository $categoriesProduitsRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $ITEM_BY_PAGE = 6;
        $LENGTH_CATEGORIES = count($categoriesProduitsRepository->findAll());

        $query = $categoriesProduitsRepository->createQueryBuilder('cp')->getQuery();

        $categoriesProduits = $paginator->paginate(
            $query,     
            $request->query->getInt('page', 1), 
            $ITEM_BY_PAGE
        );

        $currentPage = $categoriesProduits->getCurrentPageNumber();


        return $this->render('categories/index.html.twig', [
            'paginate_categories' => $categoriesProduits,
            'number_page' => ceil($LENGTH_CATEGORIES / $ITEM_BY_PAGE),
            'current_page' => $currentPage
        ]);
    }

    #[Route('/new', name: 'app_categories_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoriesProduit = new CategoriesProduits();
        $form = $this->createForm(CategoriesProduitsType::class, $categoriesProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['filePath']->getData();

            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads',
                    $fileName
                );
                $categoriesProduit->setFilePath('/uploads/'.$fileName);
            }

            $entityManager->persist($categoriesProduit);
            $entityManager->flush();

            return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categories/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categories_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategoriesProduits $categoriesProduit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoriesProduitsType::class, $categoriesProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['filePath']->getData();

            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads',
                    $fileName
                );
                $categoriesProduit->setFilePath('/uploads/'.$fileName);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categories/edit.html.twig', [
            'categorie' => $categoriesProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categories_delete', methods: ['POST'])]
    public function delete(Request $request, CategoriesProduits $categoriesProduit, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete' . $categoriesProduit->getId(), $request->request->get('_token'))) {
            $filePath = $categoriesProduit->getFilePath();

            if ($filePath) {
                // Construire le chemin absolu du fichier
                $absoluteFilePath = $this->getParameter('kernel.project_dir') . '/public' . $filePath;

                // Vérifier si le fichier existe et le supprimer s'il existe
                if (file_exists($absoluteFilePath)) {
                    unlink($absoluteFilePath);
                }
            }
            $entityManager->remove($categoriesProduit);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);

    }
}
