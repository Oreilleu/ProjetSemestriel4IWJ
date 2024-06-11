<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Factures;
use App\Entity\LignesDevis;
use App\Entity\Produits;
use App\Entity\User;
use App\Form\DevisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use DateTimeImmutable;

#[Route('/devis')]
class DevisController extends AbstractController
{
    #[Route('/', name: 'app_devis_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();
        $entityManager->initializeObject($entreprise);

        $devis = $entreprise->getDevis();

        return $this->render('devis/index.html.twig', [
            'devis' => $devis,
        ]);
    }

    #[Route('/new', name: 'app_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();

        $produits = $entreprise->getProduits();

        $devis = new Devis();
        $form = $this->createForm(DevisType::class, $devis, [
            'clients' => $entreprise->getClients(),
            'lots' => $entreprise->getLots(),
        ]);

        $form->handleRequest($request);

        $data = $request->request->all();

        if(isset($data['devis'])    ) {
            $devisData = $data['devis']; 
            $listProduitJson = $devisData['list_produit']; 
            $listProduitArray = json_decode($listProduitJson, true); 
        }
        
        if ($form->isSubmitted() && $form->isValid()) {

            $devis->setIdEntreprise($entreprise);
            $devis->setStatut('En cours');

            $totalPrice = 0;
            $taxe = $devis->getTaxe();

            foreach($listProduitArray as $product) {
                $productId = $product['productId'];
                $produit = $entityManager->getRepository(Produits::class)->find($productId);
                
                $priceWithFee = $produit->getPrix() * (1 + $taxe / 100);
                $totalPrice += $priceWithFee * $product['quantity'];
            }

            $devis->setTotalHt($totalPrice);

            $entityManager->persist($devis);

            foreach ($listProduitArray as $product) { 
                $produitId = $product['productId'];
                $produit = $entityManager->getRepository(Produits::class)->find($produitId);
                
                $lignesDevi = new LignesDevis();
                $lignesDevi->setIdProduit($produit);
                $lignesDevi->setIdDevis($devis); 
                $lignesDevi->setIdStrProduit($produitId);
                $lignesDevi->setNameProduct($produit->getNom());
                $lignesDevi->setPrixProduct($produit->getPrix());
                $lignesDevi->setQuantite($product['quantity']);
        
                $entityManager->persist($lignesDevi);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devis/new.html.twig', [
            'devi' => $devis,
            'form' => $form,
            'produits' => $produits
        ]);
    }

    #[Route('/{id}', name: 'app_devis_show', methods: ['GET'])]
    public function show(Devis $devi): Response
    {
        return $this->render('devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devis $devi, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if (!$user instanceof User || !$user) {
            return $this->redirectToRoute('app_register');
        }

        $entreprise = $user->getIdEntreprise();
        $productsAlreadyInDevis = $devi->getLignesDevis();
        $produits = $entreprise->getProduits();
        
        $form = $this->createForm(DevisType::class, $devi, [
            'show_statut_field' => true, 
            'clients' => $entreprise->getClients(),
            'lots' => $entreprise->getLots(),
        ]);

        $form->handleRequest($request);

        $data = $request->request->all();

        if(isset($data['devis'])    ) {
            $devisData = $data['devis']; 
            $listProduitJson = $devisData['list_produit']; 
            $listProduitArray = json_decode($listProduitJson, true); 
        }

        $taxe = $devi->getTaxe();
        $status = $devi->getStatut();
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            foreach ($productsAlreadyInDevis as $ligneDevis) {
                $entityManager->remove($ligneDevis);
            }
            $entityManager->flush();
            
            $totalPrice = 0;

            foreach($listProduitArray as $product) {
                $productId = $product['productId'];
                $produit = $entityManager->getRepository(Produits::class)->find($productId);

                $priceProduct = $product['price'] ? $product['price'] : $produit->getPrix();

                $priceWithFee = $priceProduct * (1 + $taxe / 100);
                $totalPrice += $priceWithFee * $product['quantity'];
            }

            $devi->setTotalHt($totalPrice);

            $entityManager->persist($devi);

            foreach ($listProduitArray as $product) { 
                $produitId = $product['productId'];
                $produit = $entityManager->getRepository(Produits::class)->find($produitId);
                $productName = $product['name'] ? $product['name'] : $produit->getNom();
                $productPrice = $product['price'] ? $product['price'] : $produit->getPrix();
                
                $lignesDevi = new LignesDevis();
                $lignesDevi->setIdDevis($devi);
                $lignesDevi->setIdProduit($produit);
                $lignesDevi->setIdStrProduit($produitId);
                $lignesDevi->setNameProduct($productName);
                $lignesDevi->setPrixProduct($productPrice);
                $lignesDevi->setQuantite($product['quantity']);
        
                $entityManager->persist($lignesDevi);
            }

            $entityManager->flush();

            if($status == 'Accepté') {
                $facture = new Factures();
                $facture->setTotalHt($devi->getTotalHt());
                $facture->setTotalTtc($devi->getTotalHt() * $devi->getTaxe()); 
                $facture->setTaxe($devi->getTaxe());
                $facture->setStatut('Pas encore payé');
                $facture->setIdDevis($devi);
                $facture->setNameClient($devi->getClient()->getNom() . ' ' . $devi->getClient()->getPrenom());
                $facture->setCreatedAt(new DateTimeImmutable());
            
                $entityManager->persist($facture);
                $entityManager->flush();

                return $this->redirectToRoute('app_factures_index', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
            }
            
        }
        
        return $this->render('devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form,
            'produits' => $produits,
            'productsAlreadyInDevis' => $productsAlreadyInDevis
        ]);
    }

    #[Route('/{id}', name: 'app_devis_delete', methods: ['POST'])]
    public function delete(Request $request, Devis $devi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($devi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
    }
}
