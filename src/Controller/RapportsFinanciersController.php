<?php

namespace App\Controller;

use App\Entity\LigneFacture;
use App\Entity\RapportsFinanciers;
use App\Entity\User;
use App\Form\RapportsFinanciersType;
use App\Repository\RapportsFinanciersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted as ConfigurationIsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/rapports-financiers')]
class RapportsFinanciersController extends AbstractController
{
    #[Route('/', name: 'app_rapports_financiers_index', methods: ['GET'])]
    public function index(RapportsFinanciersRepository $rapportsFinanciersRepository): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();

        $rapportsFinancier = $entreprise->getRapportsFinanciers();
        
        return $this->render('rapports_financiers/index.html.twig', [
            'rapports_financiers' => $rapportsFinancier,
        ]);
    }

    #[IsGranted('ROLE_COMPTABLE')]
    #[Route('/new', name: 'app_rapports_financiers_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();
        $entityManager->initializeObject($entreprise);
        $factures = $entreprise->getFactures();
        $entityManager->initializeObject($factures);

        $rapportsFinancier = new RapportsFinanciers();
        $form = $this->createForm(RapportsFinanciersType::class, $rapportsFinancier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $start_date = $data->getStartDate();
            $end_date = $data->getEndDate();
            $totalHt = 0;
            $totalTtc = 0;

            $rapportsFinancier->setCreatedAt(new \DateTimeImmutable());
            $rapportsFinancier->setIdEntreprise($entreprise);
            $rapportsFinancier->setStartDate($start_date);
            $rapportsFinancier->setEndDate($end_date);
            $rapportsFinancier->setTotalHt($totalHt);
            $rapportsFinancier->setTotalTtc($totalTtc);
    
            $entityManager->persist($rapportsFinancier);
            $entityManager->flush();

            foreach ($factures as $facture) {
                if($facture->getCreatedAt() >= $start_date && $facture->getCreatedAt() <= $end_date) {
                    $ligneFacture = new LigneFacture();
                    $ligneFacture->setIdRapportFinancier($rapportsFinancier);
                    $ligneFacture->setIdStrFactures($facture->getId());
                    $ligneFacture->setFirstnameClient($facture->getClient()->getPrenom());
                    $ligneFacture->setLastnameClient($facture->getClient()->getNom());
                    $ligneFacture->setTotalHt($facture->getTotalHt());
                    $ligneFacture->setTotalTtc($facture->getTotalTtc());
                    $ligneFacture->setCreatedAtFacture($facture->getCreatedAt());

                    $totalHt += $facture->getTotalHt();
                    $totalTtc += $facture->getTotalTtc();

                    $entityManager->persist($ligneFacture);
                }
            }

            $entityManager->flush();
            
            $rapportsFinancier->setTotalHt($totalHt);
            $rapportsFinancier->setTotalTtc($totalTtc);
    
            $entityManager->persist($rapportsFinancier);
            $entityManager->flush();

            return $this->redirectToRoute('app_rapports_financiers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rapports_financiers/new.html.twig', [
            'rapports_financier' => $rapportsFinancier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rapports_financiers_show', methods: ['GET'])]
    public function show(RapportsFinanciers $rapportsFinancier): Response
    {
        return $this->render('rapports_financiers/show.html.twig', [
            'rapports_financier' => $rapportsFinancier,
        ]);
    }

    #[IsGranted('ROLE_COMPTABLE')]
    #[Route('/{id}/edit', name: 'app_rapports_financiers_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RapportsFinanciers $rapportsFinancier, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();
        $entityManager->initializeObject($entreprise);
        $factures = $entreprise->getFactures();
        $entityManager->initializeObject($factures);

        $form = $this->createForm(RapportsFinanciersType::class, $rapportsFinancier);
        $form->handleRequest($request);

        $lastLigneFacture = $rapportsFinancier->getLignesFactures();

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            foreach ($lastLigneFacture as $ligneFacture) {
                $entityManager->remove($ligneFacture);
            }

            $start_date = $data->getStartDate();
            $end_date = $data->getEndDate();
            $totalHt = 0;
            $totalTtc = 0;

            $rapportsFinancier->setStartDate($start_date);
            $rapportsFinancier->setEndDate($end_date);
            $rapportsFinancier->setTotalHt($totalHt);
            $rapportsFinancier->setTotalTtc($totalTtc);
    
            $entityManager->persist($rapportsFinancier);
            $entityManager->flush();

            foreach ($factures as $facture) {
                if($facture->getCreatedAt() >= $start_date && $facture->getCreatedAt() <= $end_date) {
                    $ligneFacture = new LigneFacture();
                    $ligneFacture->setIdRapportFinancier($rapportsFinancier);
                    $ligneFacture->setIdStrFactures($facture->getId());
                    $ligneFacture->setFirstnameClient($facture->getClient()->getPrenom());
                    $ligneFacture->setLastnameClient($facture->getClient()->getNom());
                    $ligneFacture->setTotalHt($facture->getTotalHt());
                    $ligneFacture->setTotalTtc($facture->getTotalTtc());
                    $ligneFacture->setCreatedAtFacture($facture->getCreatedAt());

                    $totalHt += $facture->getTotalHt();
                    $totalTtc += $facture->getTotalTtc();

                    $entityManager->persist($ligneFacture);
                }
            }

            $entityManager->flush();

            $rapportsFinancier->setTotalHt($totalHt);
            $rapportsFinancier->setTotalTtc($totalTtc);
    
            $entityManager->persist($rapportsFinancier);
            $entityManager->flush();

            return $this->redirectToRoute('app_rapports_financiers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rapports_financiers/edit.html.twig', [
            'rapports_financier' => $rapportsFinancier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rapports_financiers_delete', methods: ['POST'])]
    public function delete(Request $request, RapportsFinanciers $rapportsFinancier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rapportsFinancier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rapportsFinancier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rapports_financiers_index', [], Response::HTTP_SEE_OTHER);
    }
}
