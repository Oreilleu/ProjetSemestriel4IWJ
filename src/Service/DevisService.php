<?php

namespace App\Service;

use App\Entity\Devis;
use App\Entity\Factures;
use App\Entity\LignesDevis;
use App\Entity\Produits;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;
use Twig\Environment;

class DevisService
{
    private $entityManager;
    private $emailService;
    private $interractionService;

    private  $twig;
    public function __construct(EntityManagerInterface $entityManager, EmailService $emailService, InterractionService $interractionService, Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->emailService = $emailService;
        $this->interractionService = $interractionService;
        $this->twig = $twig;
    }

    public function handleDevisCreation(Devis $devis, array $requestData, $entreprise)
    {
        $this->initializeDevis($devis, $entreprise);
        $listProduitArray = $this->getListProduitArray($requestData);

        if (empty($listProduitArray)) {
            return;
        }

        $totalPrice = $this->calculateTotalPriceHT($listProduitArray);
        $devis->setTotalHt($totalPrice);
        
        $this->entityManager->persist($devis);
        $this->interractionService->createDevisInterraction($devis, 'Un devis a été créé (id : ' . $devis->getId() . '), son statut est : ' . $devis->getStatut());
        $this->addLignesDevis($devis, $listProduitArray);
        
        $this->entityManager->flush();

        $this->sendDevisCreationEmail($devis);
    }

    private function sendDevisCreationEmail(Devis $devis): void
    {
        $subject = 'Nouveau devis créé';
        $template = 'email/create_devis.html.twig';
        $recipient = $devis->getClient()->getEmail();

        $content = $this->twig->render($template, [
            'devis' => $devis,
        ]);

        $this->emailService->sendEmail($recipient, $subject, $content);
    }

    private function sendFactureCreationEmail(Factures $facture): void
    {
        $subject = 'Nouvelle facture créée';
        $template = 'email/create_facture.html.twig';
        $recipient = $facture->getIdDevis()->getClient()->getEmail(); 

        $content = $this->twig->render($template, [
            'facture' => $facture,
        ]);

        $this->emailService->sendEmail($recipient, $subject, $content);
    }

    private function initializeDevis(Devis $devis, $entreprise)
    {
        $devis->setIdEntreprise($entreprise);
        $devis->setStatut('En cours');
    }

    private function getListProduitArray(array $requestData): array
    {
        if (isset($requestData['list_produit'])) {
            return json_decode($requestData['list_produit'], true);
        } else {
            return [];
        }
    }

    private function calculateTotalPriceHT(array $listProduitArray): float
    {
        $totalPrice = 0;

        foreach ($listProduitArray as $product) {
            $productId = $product['productId'];
            $produit = $this->entityManager->getRepository(Produits::class)->find($productId);

            if ($produit) {
                $priceProduct = $product['price'] ?? $produit->getPrix();
                $totalPrice += $priceProduct * $product['quantity'];
            }
        }

        return $totalPrice;
    }

    private function addLignesDevis(Devis $devis, array $listProduitArray)
    {
        foreach ($listProduitArray as $product) {
            $produitId = $product['productId'];
            $produit = $this->entityManager->getRepository(Produits::class)->find($produitId);
            $productName = $product['name'] ?? $produit->getNom();
            $productPrice = $product['price'] ?? $produit->getPrix();

            if ($produit) {
                $lignesDevi = new LignesDevis();
                $lignesDevi->setIdDevis($devis);
                $lignesDevi->setIdProduit($produit);
                $lignesDevi->setIdStrProduit($produitId);
                $lignesDevi->setNameProduct($productName);
                $lignesDevi->setPrixProduct($productPrice);
                $lignesDevi->setQuantite($product['quantity']);

                $this->entityManager->persist($lignesDevi);
            }
        }
    }

    public function validateDevis($devi)
    {
        if ($devi->getStatut() === 'Accepté') {
            return ['route' => 'app_devis_show', 'params' => ['id' => $devi->getId()]];
        }
    }

    public function prepareEditData($devi, $user)
    {
        $entreprise = $user->getIdEntreprise();
        $productsAlreadyInDevis = $devi->getLignesDevis();
        $produits = $entreprise->getProduits();

        return [
            'entreprise' => $entreprise,
            'productsAlreadyInDevis' => $productsAlreadyInDevis,
            'produits' => $produits
        ];
    }

    public function handleFormSubmission($devi, $requestData)
    {
        $listProduitArray = $this->getListProduitArray($requestData);
        $status = $devi->getStatut();

        $this->removeOldLignesDevis($devi);

        $totalPrice = $this->calculateTotalPriceHT($listProduitArray);
        $devi->setTotalHt($totalPrice);

        $this->entityManager->persist($devi);
        $this->addLignesDevis($devi, $listProduitArray);
        $this->interractionService->createDevisInterraction($devi, 'Un devis a été modifié (id : ' . $devi->getId() . '), son statut est : ' . $status);

        $this->entityManager->flush();

        if ($status === 'Accepté') {
            $this->updateFacture($devi);
            return 'facture';
        }

        return 'devis';
    }

    private function removeOldLignesDevis(Devis $devi)
    {
        foreach ($devi->getLignesDevis() as $ligneDevis) {
            $this->entityManager->remove($ligneDevis);
        }
        $this->entityManager->flush();
    }

    private function updateFacture(Devis $devi)
    {
        $this->entityManager->refresh($devi);
        $lignesDevis = $devi->getLignesDevis();

        $facture = new Factures();
        $facture->setIdEntreprise($devi->getIdEntreprise());
        $facture->setTotalHt($devi->getTotalHt());
        $facture->setTotalTtc($devi->getTotalHt() * (1 + $devi->getTaxe() / 100));
        $facture->setTaxe($devi->getTaxe());
        $facture->setStatut('En cours de paiement');
        $facture->setIdDevis($devi);
        $facture->setClient($devi->getClient());
        $facture->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($facture);

        $this->interractionService->createFactureInterraction($facture, 'Une facture a été créée (id : ' . $facture->getId() . '), son statut est : ' . $facture->getStatut());

        foreach ($lignesDevis as $ligne) {
            $ligne->setIdFactures($facture);
            $this->entityManager->persist($ligne);
        }

        $this->entityManager->flush();

        $this->sendFactureCreationEmail($facture);
    }
}
