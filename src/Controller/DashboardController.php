<?php

namespace App\Controller;

use App\Repository\DevisRepository;
use App\Repository\FacturesRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClientsRepository;

class DashboardController extends AbstractController
{

    private $clientsRepository;
    private $facturesRepository;

    private $devisRepository;

    public function __construct(ClientsRepository $clientsRepository, FacturesRepository $facturesRepository, DevisRepository $devisRepository)
    {
        $this->clientsRepository = $clientsRepository;
        $this->facturesRepository = $facturesRepository;
        $this->devisRepository = $devisRepository;
    }

    #[Route('/dashboard', name: 'app_dashboard_index')]
    public function index(EntityManagerInterface $entityManager, ClientsRepository $clientsRepository): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('home');
        }

        $entreprise = $user->getIdEntreprise();

        $numberDevis = $entreprise->getDevis()->count();

        $clients = $entreprise->getClients();

        $produits = $entreprise->getProduits();

        $numberDevisAccept = $entreprise->getDevis()->filter(function ($devis) {
            return $devis->getStatut() === 'Accepté';
        })->count();

        $numberFactures = $entreprise->getFactures()->count();

        $numberClients = $clients->count();

        $numberProduits = $produits->count();

        $recentClients = $clients->slice(-3);

        $recentProduits = $produits->slice(-3);

        // $sumAllPaiement = $entreprise->getFactures()->map(function($facture) {
        //     return $facture->getPaiements()->map(function($paiement) {
        //         return $paiement->getMontant();
        //     })->sum();
        // });

        $sumAllPaiement = 0;
        foreach ($entreprise->getFactures() as $facture) {
            foreach ($facture->getPaiements() as $paiement) {
                $sumAllPaiement += $paiement->getMontant();
            }
        }

        $sumDevisAccept = 550;

        $turnOver = 1500;

        $devisData = $this->devisRepository->countByMonthAndEntreprise($entreprise->getId());

        // Initialisation du tableau avec des zéros pour chaque mois
        $devisDataFormatted = array_fill(1, 12, 0);
        foreach ($devisData as $data) {
            $devisDataFormatted[intval($data['month'])] = $data['count'];
        }

        $facturesData = $this->facturesRepository->countByMonthAndEntreprise($entreprise->getId());

        // Transformer les données pour le graphique
        $facturesDataFormatted = array_fill(1, 12, 0); // Initialise un tableau avec 12 mois (de janvier à décembre)
        foreach ($facturesData as $data) {
            $facturesDataFormatted[intval($data['month'])] = $data['count'];
        }


        // dd($numberDevisAccept);

        // Je veux renvoyer : 
        // Nombre de devis de l'entreprise
        // Nombre de devis accepté 
        // Nombre de factures 

        // Somme des paiement recu
        // Somme des devis accepté
        // Chiffre d'affaire

        return $this->render('dashboard/index.html.twig', [
            'numberDevis' => $numberDevis,
            'numberProduits' => $numberProduits,
            'numberClients' => $numberClients,
            'numberDevisAccept' => $numberDevisAccept,
            'numberFactures' => $numberFactures,
            'recentClients' => $recentClients,
            'recentProduits' => $recentProduits,
            'sumAllPaiement' => $sumAllPaiement,
            'sumDevisAccept' => $sumDevisAccept,
            'turnOver' => $turnOver,
            'facturesData' => json_encode(array_values($facturesDataFormatted)),
            'devisData' => json_encode(array_values($devisDataFormatted)),
        ]);
    }
}