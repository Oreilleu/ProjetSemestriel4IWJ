<?php

namespace App\Repository;

use App\Entity\RapportFinanciers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RapportFinanciers>
 *
 * @method RapportFinanciers|null find($id, $lockMode = null, $lockVersion = null)
 * @method RapportFinanciers|null findOneBy(array $criteria, array $orderBy = null)
 * @method RapportFinanciers[]    findAll()
 * @method RapportFinanciers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapportFinanciersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RapportFinanciers::class);
    }

//    /**
//     * @return RapportFinanciers[] Returns an array of RapportFinanciers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RapportFinanciers
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
