<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserEntreprisesType;
use App\Repository\UserRepository;

#[Route('/users')]
class UsersEntreprisesController extends AbstractController
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    private function instanceOfEntreprise(User $user): bool
    {
        $currentUser = $this->getUser();

        if (!$currentUser instanceof User) {
            return false;
        }

        $entreprise = $currentUser->getIdEntreprise();

        return $user->getIdEntreprise() === $entreprise;
    }

    #[Route('/', name: 'app_users_index', methods: ['GET'])]
    public function index(): Response
    {
        
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_register');
        }
        $entreprise = $user->getIdEntreprise();

        $users = $entreprise->getUsers();

        // exclude current user from the list

        $users = array_filter($users->toArray(), function ($u) use ($user) {
            return $u->getId() !== $user->getId();
        });

        return $this->render('usersEntreprises/index.html.twig', [
            'users' => $users
        ]);
    }

    # Ajouter un utilsateur de l'entreprise
    #[Route('/new', name: 'app_user_add', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();


        if (!$user instanceof User) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();

        $newUser = new User();

        $form = $this->createForm(UserEntreprisesType::class, $newUser);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $newUser->setPassword($this->passwordHasher->hashPassword($newUser, $newUser->getPassword()));
            $newUser->setIdEntreprise($entreprise);
            $entityManager->persist($newUser);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été ajouté avec succès.');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('usersEntreprises/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        
        if (!$this->instanceOfEntreprise($user)) {
            return $this->redirectToRoute('app_users_index');
        }

        return $this->render('usersEntreprises/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if (!$this->instanceOfEntreprise($user)) {
            return $this->redirectToRoute('app_users_index');
        }

        $form = $this->createForm(UserEntreprisesType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été modifié avec succès.');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('usersEntreprises/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if (!$this->instanceOfEntreprise($user)) {
            return $this->redirectToRoute('app_users_index');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_account');
    }
}

