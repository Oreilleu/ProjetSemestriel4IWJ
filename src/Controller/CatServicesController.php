<?php

namespace App\Controller;

use App\Entity\CatServices;
use App\Form\CatServicesType;
use App\Repository\CatServicesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/cat-services')]
class CatServicesController extends AbstractController
{
    #[Route('/', name: 'app_cat_services_index', methods: ['GET'])]
    public function index(CatServicesRepository $catServicesRepository, PaginatorInterface $paginator, Request $request, LoggerInterface $logger): Response
    {

        $ITEM_BY_PAGE = 6;
        $LENGTH_CAT_SERVICES = count($catServicesRepository->findAll());

        $query = $catServicesRepository->createQueryBuilder('catService')->getQuery();

        $catServices = $paginator->paginate(
            $query,     
            $request->query->getInt('page', 1), 
            $ITEM_BY_PAGE
        );

        $currentPage = $catServices->getCurrentPageNumber();


        return $this->render('cat_services/index.html.twig', [
            'paginate_cat_services' => $catServices,
            'number_page' => ceil($LENGTH_CAT_SERVICES / $ITEM_BY_PAGE),
            'current_page' => $currentPage
        ]);
    }

    #[Route('/new', name: 'app_cat_services_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $catService = new CatServices();
        $form = $this->createForm(CatServicesType::class, $catService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['filePath']->getData();

            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads',
                    $fileName
                );
                $catService->setFilePath('/uploads/'.$fileName);
            }

            $entityManager->persist($catService);
            $entityManager->flush();

            return $this->redirectToRoute('app_cat_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cat_services/new.html.twig', [
            'cat_service' => $catService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cat_services_show', methods: ['GET'])]
    public function show(CatServices $catService): Response
    {
        return $this->render('cat_services/show.html.twig', [
            'cat_service' => $catService,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cat_services_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CatServices $catService, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CatServicesType::class, $catService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['filePath']->getData();

            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads',
                    $fileName
                );
                $catService->setFilePath('/uploads/'.$fileName);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_cat_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cat_services/edit.html.twig', [
            'cat_service' => $catService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cat_services_delete', methods: ['POST'])]
    public function delete(Request $request, CatServices $catService, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete' . $catService->getId(), $request->request->get('_token'))) {
            $filePath = $catService->getFilePath();

            if ($filePath) {
                // Construire le chemin absolu du fichier
                $absoluteFilePath = $this->getParameter('kernel.project_dir') . '/public' . $filePath;

                // Vérifier si le fichier existe et le supprimer s'il existe
                if (file_exists($absoluteFilePath)) {
                    unlink($absoluteFilePath);
                }
            }
            $entityManager->remove($catService);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('app_cat_services_index', [], Response::HTTP_SEE_OTHER);

    }
}
