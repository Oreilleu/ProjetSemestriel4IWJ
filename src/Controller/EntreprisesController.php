<?php

namespace App\Controller;

use App\Entity\Entreprises;
use App\Form\EntreprisesType;
use App\Repository\EntreprisesRepository;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/entreprises')]
class EntreprisesController extends AbstractController
{
    private $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }
    #[Route('/', name: 'app_entreprises_index', methods: ['GET'])]
    public function index(EntreprisesRepository $entreprisesRepository): Response
    {
        $entreprises = $entreprisesRepository->findAll();

        return $this->render('entreprises/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    #[Route('/generate-pdf', name: 'app_generate_pdf', methods: ['GET'])]
    public function generatePdfAction(EntreprisesRepository $entreprisesRepository): Response
    {
        $entreprises = $entreprisesRepository->findAll();


        $pdfHtml = $this->renderView('pdf/entreprises_pdf.html.twig', [
            'entreprises' => $entreprises,
        ]);


        $pdfContent = $this->pdfService->generatePdf($pdfHtml);


        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="liste_entreprises.pdf"',
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
        }

        return $this->redirectToRoute('app_entreprises_index', [], Response::HTTP_SEE_OTHER);
    }
}
