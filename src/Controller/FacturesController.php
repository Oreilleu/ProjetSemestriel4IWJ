<?php

namespace App\Controller;

use App\Entity\Factures;
use App\Entity\Paiements;
use App\Form\FacturesType;
use App\Form\PaiementsType;
use App\Repository\FacturesRepository;
use App\Service\EmailService;
use App\Service\InterractionService;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/factures')]
class FacturesController extends AbstractController
{

    private $interractionService;
    private $emailService;

    public function __construct(InterractionService $interractionService, EmailService $emailService)
    {
        $this->interractionService = $interractionService;
        $this->emailService = $emailService;
    }

    #[Route('/', name: 'app_factures_index', methods: ['GET'])]
    public function index(FacturesRepository $facturesRepository): Response
    {
        return $this->render('factures/index.html.twig', [
            'factures' => $facturesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_factures_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $facture = new Factures();
        $form = $this->createForm(FacturesType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_factures_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('factures/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_factures_show', methods: ['GET'])]
    public function show(Factures $facture): Response
    {
        return $this->render('factures/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_factures_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Factures $facture, EntityManagerInterface $entityManager): Response
    {
        $totalTtcFacture = $facture->getTotalTtc();
        $paiements = $facture->getPaiements();
        $totalPaid = array_reduce($paiements->toArray(), fn($carry, $paiement) => $carry + $paiement->getMontant(), 0);
        $maxAmount = $totalTtcFacture - $totalPaid;

        
        $form = $this->createForm(PaiementsType::class, new Paiements(), [
            'max_amount' => $maxAmount,
        ]);

        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {
            $paiement = $form->getData();
            $paiement->setCreatedAt(new \DateTimeImmutable());
            $paiement->setIdFacture($facture);
            
            $this->processPayment($paiement, $facture, $maxAmount);

            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute('app_factures_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('factures/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_factures_delete', methods: ['POST'])]
    public function delete(Request $request, Factures $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_factures_index', [], Response::HTTP_SEE_OTHER);
    }

    private function processPayment(Paiements $paiement, Factures $facture, float $maxAmount): void
    {
        $isFullyPaid = $maxAmount === $paiement->getMontant();
        $facture->setStatut($isFullyPaid ? 'Payée' : 'Partiellement payée');

        $message = $isFullyPaid
            ? "Un paiement clôturant la facture, d'un montant de {$paiement->getMontant()} € a été effectué pour la facture : {$facture->getId()}."
            : "Un paiement partiel, d'un montant de {$paiement->getMontant()} € a été effectué pour la facture : {$facture->getId()}.";

        $this->sendPaymentEmail($facture, $isFullyPaid);
        $this->interractionService->createFactureInterraction($facture, $message);
    }

    private function sendPaymentEmail(Factures $facture, bool $isFullyPaid): void
    {
        $subject = 'Nouveau paiement effectué';
        $content = $isFullyPaid
            ? "Un nouveau paiement a été effectué pour la facture : {$facture->getId()}. La facture est totalement payée."
            : "Un paiement partiel a été effectué pour la facture : {$facture->getId()}.";
        $recipient = $facture->getIdDevis()->getClient()->getEmail();

        $this->emailService->sendEmail($recipient, $subject, $content);
    }
    
}
