<?php

namespace App\Controller;

use App\Repository\ClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class DashboardController extends AbstractController
{
    private $clientsRepository;

    public function __construct(ClientsRepository $clientsRepository)
    {
        $this->clientsRepository = $clientsRepository;
    }

    #[Route('/dashboard', name: 'app_dashboard_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Rediriger si l'utilisateur n'est pas connecté
        if (!$user instanceof User) {
            return $this->redirectToRoute('home');
        }

        // Récupérer l'entreprise de l'utilisateur
        $entreprise = $user->getIdEntreprise();
        $entityManager->initializeObject($entreprise);

        // Calcul des statistiques
        $numberDevis = $entreprise->getDevis()->count();
        $numberClients = $entreprise->getClients()->count();
        $numberDevisAccept = $entreprise->getDevis()->filter(function($devis) {
            return $devis->getStatut() === 'Accepté';
        })->count();
        $numberFactures = $entreprise->getFactures()->count();

        // Récupérer le nombre total de clients
        $totalClients = $this->clientsRepository->countClientsByEntreprise($entreprise);

        // Récupérer les trois derniers clients
        $recentClients = $this->clientsRepository->findLatestClients();

        // A remplacer par des calculs réels.
        $sumAllPaiement = 1000;
        $sumDevisAccept = 550;
        $turnOver = 1500;

        // Renvoyer les données au template
        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
            'numberDevis' => $numberDevis,
            'numberClients' => $numberClients,
            'totalClients' => $totalClients,
            'recentClients' => $recentClients,
            'numberDevisAccept' => $numberDevisAccept,
            'numberFactures' => $numberFactures,
            'sumAllPaiement' => $sumAllPaiement,
            'sumDevisAccept' => $sumDevisAccept,
            'turnOver' => $turnOver,
        ]);
    }
}
