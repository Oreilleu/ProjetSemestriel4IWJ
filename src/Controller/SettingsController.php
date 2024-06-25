<?php

namespace App\Controller;

use App\Entity\Entreprises;
use App\Entity\User;
use App\Form\EntreprisesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    #[Route(path: '/parametres', name: 'app_parametres')]
    public function login(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();

        $form = $this->createForm(EntreprisesType::class, $entreprise, [
            'onSettingsPage' => true,
        ]);

        $form->handleRequest($request);

        return $this->render('parametres/index.html.twig', [
            'form' => $form->createView(), 
        ]);
    }

}
