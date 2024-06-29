<?php

namespace App\Repository;

use App\Entity\Devis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Devis>
 *
 * @method Devis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Devis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Devis[]    findAll()
 * @method Devis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Devis::class);
        $this->entityManager = $entityManager;
    }

//    /**
//     * @return Devis[] Returns an array of Devis objects
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

//    public function findOneBySomeField($value): ?Devis
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function countByMonthAndEntreprise(int $entrepriseId): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('month', 'month');
        $rsm->addScalarResult('count', 'count');

        $query = $this->entityManager->createNativeQuery(
            'SELECT DATE_PART(\'month\', d.date) as month, 
                    COUNT(d.id) as count
             FROM devis d
             WHERE d.id_entreprise_id = :entrepriseId
             GROUP BY DATE_PART(\'month\', d.date)
             ORDER BY DATE_PART(\'month\', d.date)',
            $rsm
        )->setParameter('entrepriseId', $entrepriseId);

        return $query->getResult();
    }
}
