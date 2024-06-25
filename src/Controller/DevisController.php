<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\User;
use App\Form\DevisType;
use App\Service\DevisService;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/devis')]
class DevisController extends AbstractController
{
    private $devisService;

    public function __construct(DevisService $devisService)
    {
        $this->devisService = $devisService;
    }
    
    #[Route('/', name: 'app_devis_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();
        $entityManager->initializeObject($entreprise);

        $devis = $entreprise->getDevis();

        return $this->render('devis/index.html.twig', [
            'devis' => $devis,
        ]);
    }

    #[Route('/{id}/devis/pdf', name: 'app_devis_pdf', methods: ['GET'])]
    public function generatePdf(Devis $devi, PdfService $pdfService): Response
    {
        $html = $this->renderView('pdf/devis.html.twig', [
            'devi' => $devi,
            'entreprise' => $devi->getIdEntreprise(),
            'client' => $devi->getClient()
        ]);

        return new Response(
            $pdfService->generatePdf($html),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }

    #[Route('/{id}/download', name: 'app_devis_download_pdf', methods: ['GET'])]
    public function downloadPdf(Devis $devi, PdfService $pdfService): Response
    {
        $html = $this->renderView('pdf/devis.html.twig', [
            'devi' => $devi,
            'entreprise' => $devi->getIdEntreprise(),
            'client' => $devi->getClient()
        ]);

        $filename = 'devis-' . $devi->getId() . '-' . $devi->getClient()->getNom() . '-' . $devi->getClient()->getPrenom();

        $pdfService->downloadPdf($html, $filename);

        return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/new', name: 'app_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();
        $produits = $entreprise->getProduits();
        
        $devis = new Devis();
        $form = $this->createForm(DevisType::class, $devis, [
            'clients' => $entreprise->getClients(),
            'lots' => $entreprise->getLots(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requestData = $request->request->all();

            $this->devisService->handleDevisCreation($devis, $requestData['devis'], $entreprise);

            return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devis/new.html.twig', [
            'devi' => $devis,
            'form' => $form->createView(),
            'produits' => $produits
        ]);
    }

    #[Route('/{id}', name: 'app_devis_show', methods: ['GET'])]
    public function show(Devis $devi): Response
    {
        return $this->render('devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devis $devi, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        
        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $validationResult = $this->devisService->validateDevis($devi);

        if (isset($validationResult['route'])) {
            return $this->redirectToRoute($validationResult['route'], $validationResult['params'] ?? []);
        }

        $data = $this->devisService->prepareEditData($devi, $user);

        $form = $this->createForm(DevisType::class, $devi, [
            'show_statut_field' => true,
            'clients' => $data['entreprise']->getClients(),
            'lots' => $data['entreprise']->getLots(),
        ]);

        $form->handleRequest($request);
        $requestData = $request->request->all();

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->devisService->handleFormSubmission($devi, $requestData['devis']);

            if ($result === 'facture') {
                return $this->redirectToRoute('app_factures_index', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form,
            'produits' => $data['produits'],
            'productsAlreadyInDevis' => $data['productsAlreadyInDevis'],
        ]);
    }
        
    #[Route('/{id}', name: 'app_devis_delete', methods: ['POST'])]
    public function delete(Request $request, Devis $devi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devi->getId(), $request->request->get('_token'))) {

            $lignesDevis = $devi->getLignesDevis();
            foreach($lignesDevis as $ligneDevis) {

                if(!$ligneDevis->getIdFactures()) {
                    $entityManager->remove($ligneDevis);
                }
            }

            $entityManager->remove($devi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
    }
}
