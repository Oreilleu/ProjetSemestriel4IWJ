<?php

namespace App\Service;

use App\Entity\Clients;
use App\Entity\Devis;
use App\Entity\Factures;
use App\Entity\Interractions;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class InterractionService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createDevisInterraction(Devis $devis, string $content)
    {
        $interraction = new Interractions();
        $interraction->setIdClient($devis->getClient());
        $interraction->setIdDevis($devis);
        $interraction->setContent($content);
        $interraction->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($interraction);
        $devis->addInterraction($interraction);
    }

    public function createFactureInterraction(Factures $facture, string $content, Clients $idClient)
    {
        $interraction = new Interractions();
        $interraction->setIdClient($idClient);
        $interraction->setIdFactures($facture);
        $interraction->setContent($content);
        $interraction->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($interraction);
        $facture->addInterraction($interraction);
    }

}