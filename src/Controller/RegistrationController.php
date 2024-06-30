<?php

namespace App\Controller;

use App\Entity\Entreprises;
use App\Entity\User;
use App\Form\EntreprisesType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request): Response
    {
        $entreprise = new Entreprises();

        $form = $this->createForm(EntreprisesType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entreprise = $form->getData();
            $request->getSession()->set('entreprise', $entreprise);
            return $this->redirectToRoute('app_register_user');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('/register/user', name: 'app_register_user')]
    public function registerUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $entreprise = $request->getSession()->get('entreprise');

        if (!$entreprise) {
            return $this->redirectToRoute('app_register');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entreprise);
            $user->setIdEntreprise($entreprise);

            $request->getSession()->remove('entreprise');

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setRoles(['ROLE_ADMIN_ENTREPRISE']);

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre compte a bien été créé. Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('app_login');

        }

        return $this->render('registration/user.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
