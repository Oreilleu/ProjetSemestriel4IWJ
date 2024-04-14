<?php

namespace App\Repository;

use App\Entity\DetailsServices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetailsServices>
 *
 * @method DetailsServices|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailsServices|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailsServices[]    findAll()
 * @method DetailsServices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailsServicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailsServices::class);
    }

//    /**
//     * @return DetailsServices[] Returns an array of DetailsServices objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DetailsServices
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
