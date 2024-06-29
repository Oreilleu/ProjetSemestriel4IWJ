<?php

namespace App\Repository;

use App\Entity\Clients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Clients>
 *
 * @method Clients|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clients|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clients[]    findAll()
 * @method Clients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clients::class);
    }

    public function findByCritere(string $searchkey): array
{
    return $this->createQueryBuilder('a')
        ->andWhere('UPPER(a.nom) LIKE :val')
        ->orWhere('UPPER(a.prenom) LIKE :val')
        ->setParameter('val', '%'.strtoupper($searchkey).'%')
        ->orderBy('a.nom', 'ASC')
        ->getQuery()
        ->execute();
}

 // Nouvelle méthode pour récupérer les trois derniers clients
 public function findLatestClients(): array
 {
     return $this->createQueryBuilder('c')
         ->orderBy('c.id', 'DESC') // ou par date de création si disponible, par ex. 'c.createdAt'
         ->setMaxResults(3)
         ->getQuery()
         ->getResult();
 }


 public function countClientsByEntreprise($entreprise)
{
    return $this->createQueryBuilder('c')
        ->select('count(c.id)')
        ->where('c.id_entreprise = :entreprise')
        ->setParameter('entreprise', $entreprise)
        ->getQuery()
        ->getSingleScalarResult();
}

//    /**
//     * @return Clients[] Returns an array of Clients objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Clients
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
