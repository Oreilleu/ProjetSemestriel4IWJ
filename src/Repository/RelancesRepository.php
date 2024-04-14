<?php

namespace App\Repository;

use App\Entity\Relances;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Relances>
 *
 * @method Relances|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relances|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relances[]    findAll()
 * @method Relances[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelancesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relances::class);
    }

//    /**
//     * @return Relances[] Returns an array of Relances objects
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

//    public function findOneBySomeField($value): ?Relances
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
