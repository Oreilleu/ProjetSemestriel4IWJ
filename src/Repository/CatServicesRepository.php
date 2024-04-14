<?php

namespace App\Repository;

use App\Entity\CatServices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CatServices>
 *
 * @method CatServices|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatServices|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatServices[]    findAll()
 * @method CatServices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatServicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CatServices::class);
    }

//    /**
//     * @return CatServices[] Returns an array of CatServices objects
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

//    public function findOneBySomeField($value): ?CatServices
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
