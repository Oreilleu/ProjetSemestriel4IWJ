<?php

namespace App\Controller;

use App\Entity\Interractions;
use App\Entity\User;
use App\Form\InterractionsType;
use App\Repository\InterractionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/interractions')]
class InterractionsController extends AbstractController
{
    #[Route('/', name: 'app_interractions_index', methods: ['GET'])]
    public function index(InterractionsRepository $interractionsRepository, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        if(!$user instanceof User) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();

        $entityManager->initializeObject($entreprise);

        $clients = $entreprise->getClients();

        $interractions = [];

        foreach ($clients as $client) {
            foreach ($client->getInterraction() as $interraction) {
                $interractions[] = $interraction;
            }
        }

        return $this->render('interractions/index.html.twig', [
            'interractions' => $interractions,
        ]);
    }

    // #[Route('/{id}', name: 'app_interractions_show', methods: ['GET'])]
    // public function show(Interractions $interraction): Response
    // {
    //     return $this->render('interractions/show.html.twig', [
    //         'interraction' => $interraction,
    //     ]);
    // }

}
