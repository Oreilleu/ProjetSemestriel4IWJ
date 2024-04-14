<?php

namespace App\Repository;

use App\Entity\Interractions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Interractions>
 *
 * @method Interractions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Interractions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Interractions[]    findAll()
 * @method Interractions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterractionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interractions::class);
    }

//    /**
//     * @return Interractions[] Returns an array of Interractions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Interractions
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
