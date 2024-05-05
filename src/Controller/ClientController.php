<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Clients;
use App\Form\ClientType;

#[Route('/client', name: 'client_')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {        
        return $this->render('client/index.html.twig');
    }


    #[Route('/ajouter', name: 'ajouter')]
    public function ajouter(Request $request): Response
    {
        $client = new Clients();
        $form = $this-> createForm(ClientType::class, $client);
        $form -> handleRequest($request);

            if($form ->isSubmitted() && $form->isValid()){
                $entityManager = $this->getDoctrine()-> getManager();
                $entityManager->persist($client);
                $entityManager-> flush();

                return $this-> redirectToRoute('client_index');
            }

            return $this -> render('client/ajouter.html.twig', [
                'form' => $form-> createView(),
            ]);
    }


    #[Route('/modifier/{id}', name: 'modifier')]
    public function modifier(Request $request, $id): Response
    {
        $client = $this->getDoctrine() -> getRepository(Clients::class)-> find($id);
        $form = $this-> createForm(ClientType::class, $client);
        $form-> handleRequest($request);

        if($form -> isSubmitted() && $form-> isValid()){
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/modifier.html.twig', [
            'client'=> $client,
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function supprimer(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $client = $entityManager->getRepository(Clients::class)->find($id);

        if(!$client) {
            throw $this->createNotFoundException('Client non trouvé');
        }

        $entityManager -> remove($client);
        $entityManager -> flush();

        return $this->redirectToRoute('client_index');
    }

}
