<?php

namespace App\Service;

use App\Entity\Clients;
use App\Entity\Devis;
use App\Entity\Factures;
use App\Entity\Interractions;
use App\Entity\LignesDevis;
use App\Entity\Produits;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;

class DevisService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        $this->createDevisInterraction($devis, 'Un devis a été créé (id : ' . $devis->getId() . '), son statut est : ' . $devis->getStatut());
        $this->addLignesDevis($devis, $listProduitArray);
        
        $this->entityManager->flush();
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

    private function createDevisInterraction(Devis $devis, string $content)
    {
        $interraction = new Interractions();
        $interraction->setIdClient($devis->getClient());
        $interraction->setIdDevis($devis);
        $interraction->setContent($content);
        $interraction->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($interraction);
        $devis->addInterraction($interraction);
    }

    private function createFactureInterraction(Factures $facture, string $content, Clients $idClient)
    {
        $interraction = new Interractions();
        $interraction->setIdClient($idClient);
        $interraction->setIdFactures($facture);
        $interraction->setContent($content);
        $interraction->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($interraction);
        $facture->addInterraction($interraction);
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
        $this->createDevisInterraction($devi, 'Un devis a été modifié (id : ' . $devi->getId() . '), son statut est : ' . $status);

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
        $facture->setTotalHt($devi->getTotalHt());
        $facture->setTotalTtc($devi->getTotalHt() * (1 + $devi->getTaxe() / 100));
        $facture->setTaxe($devi->getTaxe());
        $facture->setStatut('En cours de paiement');
        $facture->setIdDevis($devi);
        $facture->setNameClient($devi->getClient()->getNom() . ' ' . $devi->getClient()->getPrenom());
        $facture->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($facture);

        $this->createFactureInterraction($facture, 'Une facture a été créée (id : ' . $facture->getId() . '), son statut est : ' . $facture->getStatut(), $devi->getClient());

        foreach ($lignesDevis as $ligne) {
            $ligne->setIdFactures($facture);
            $this->entityManager->persist($ligne);
        }

        $this->entityManager->flush();
    }
}
