<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
use App\Form\EntreprisesType;

#[Route('/account')]
class AccountController extends AbstractController
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    
    #[Route('/', name: 'app_account')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_register');
        }
        
        $formUser = $this->createForm(UserType::class, $user);
        $formUser->handleRequest($request);
        
        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Vos informations ont été mises à jour.');
            return $this->redirectToRoute('app_account');
        }

        $entreprise = $user->getIdEntreprise();

        $formEntreprise = $this->createForm(EntreprisesType::class, $entreprise);
        $formEntreprise->handleRequest($request);

        if($formEntreprise->isSubmitted() && $formEntreprise->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Les informations de l\'entreprise ont été mises à jour.');
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/index.html.twig', [
            'formUser' => $formUser->createView(),
            'formEntreprise' => $formEntreprise->createView()
        ]);
    }

    #[Route('/change-password', name: 'app_account_change_password')]
    public function changePassword(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_register');
        }

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentPassword = $form->get('current_password')->getData();

            if ($this->passwordHasher->isPasswordValid($user, $currentPassword)) {
                $newPassword = $form->get('new_password')->getData();
                $user->setPassword($this->passwordHasher->hashPassword($user, $newPassword));

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre mot de passe a été changé avec succès.');

                return $this->redirectToRoute('app_account');
            } else {
                $this->addFlash('error', 'Votre mot de passe actuel est incorrect.');
            }
        }
        
        return $this->render('account/change_password.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }
}