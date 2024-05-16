<?php

namespace App\Repository;

use App\Entity\RapportsFinanciers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RapportsFinanciers>
 *
 * @method RapportsFinanciers|null find($id, $lockMode = null, $lockVersion = null)
 * @method RapportsFinanciers|null findOneBy(array $criteria, array $orderBy = null)
 * @method RapportsFinanciers[]    findAll()
 * @method RapportsFinanciers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapportsFinanciersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RapportsFinanciers::class);
    }

//    /**
//     * @return RapportsFinanciers[] Returns an array of RapportsFinanciers objects
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

//    public function findOneBySomeField($value): ?RapportsFinanciers
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
