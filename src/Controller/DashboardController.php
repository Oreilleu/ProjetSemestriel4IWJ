<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard_index')]
    public function index()
    {
        $user = $this->getUser();

        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('home');
        }

        $entreprise = $user->getIdEntreprise();

        $numberDevis = $entreprise->getDevis()->count();

        $numberDevisAccept = $entreprise->getDevis()->filter(function($devis) {
            return $devis->getStatut() === 'Accepté';
        })->count();

        $numberFactures = $entreprise->getFactures()->count();

        $sumAllPaiement = array_sum($entreprise->getFactures()->map(function($facture) {
            return array_sum($facture->getPaiements()->map(function($paiement) {
                return $paiement->getMontant();
            })->toArray());
        })->toArray());

        $sumDevisAcceptHt = array_sum($entreprise->getDevis()->filter(function($devis) {
            return $devis->getStatut() === 'Accepté';
        })->map(function($devis) {
            return $devis->getTotalHt();
        })->toArray());

        $quantites = $entreprise->getFactures()->filter(function($facture) {
            return $facture->getStatut() === 'Payée';
        })->map(function($facture) {
            $lignesDevis = $facture->getLignesDevis();
            if (!$lignesDevis->isInitialized()) {
                $lignesDevis->initialize(); 
            }
            return $lignesDevis->map(function($ligneDevis) {
                return $ligneDevis->getQuantite();
            })->toArray();
        })->toArray();

        $mergeQuantite = array_merge(...$quantites);

        $numberProducts = array_sum($mergeQuantite);
        
        return $this->render('dashboard/index.html.twig', [
            'numberDevis' => $numberDevis,
            'numberDevisAccept' => $numberDevisAccept,
            'numberFactures' => $numberFactures,
            'sumAllPaiement' => $sumAllPaiement,
            'sumDevisAcceptHt' => $sumDevisAcceptHt,
            'numberProducts' => $numberProducts
        ]);
    }
}