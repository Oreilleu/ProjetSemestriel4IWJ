<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EntreprisesType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    #[Route(path: '/parametres', name: 'app_parametres')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
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

        $formUser = $this->createForm(UserType::class, $user, [
            'onSettingsPage' => true,
        ]);
        $formUser->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entreprise);
            $entityManager->flush();
            return $this->redirectToRoute('app_parametres');
        }

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_parametres');
        }

        return $this->render('parametres/index.html.twig', [
            'form' => $form->createView(),
            'formUser' => $formUser->createView(),
        ]);
    }

}
