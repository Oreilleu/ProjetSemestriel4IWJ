<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClientsRepository;

class DashboardController extends AbstractController
{

    private $clientsRepository;

    public function __construct(ClientsRepository $clientsRepository)
    {
        $this->clientsRepository = $clientsRepository;
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

        $numberClients = $entreprise->getClients()->count();

        $numberDevisAccept = $entreprise->getDevis()->filter(function($devis) {
            return $devis->getStatut() === 'Accepté';
        })->count();

        $numberFactures = $entreprise->getFactures()->count();



        $entityManager->initializeObject($entreprise);

        $totalClients = $clientsRepository->countClientsByEntreprise($entreprise);


        // Récupérer les trois derniers clients
        $recentClients = $this->clientsRepository->findLatestClients();

        // $sumAllPaiement = $entreprise->getFactures()->map(function($facture) {
        //     return $facture->getPaiements()->map(function($paiement) {
        //         return $paiement->getMontant();
        //     })->sum();
        // });

        $sumAllPaiement = 1000;

        $sumDevisAccept = 550;

        $turnOver = 1500;


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
            'numberClients' => $numberClients,
            'totalClients' => $totalClients,
            'recentClients' => $recentClients,
            'numberDevisAccept' => $numberDevisAccept,
            'numberFactures' => $numberFactures,
            'sumAllPaiement' => $sumAllPaiement,
            'sumDevisAccept' => $sumDevisAccept,
            'turnOver' => $turnOver
        ]);
    }
}