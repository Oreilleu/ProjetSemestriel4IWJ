<?php

namespace App\Repository;

use App\Entity\Factures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Factures>
 *
 * @method Factures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Factures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Factures[]    findAll()
 * @method Factures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Factures::class);
    }

//    /**
//     * @return Factures[] Returns an array of Factures objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Factures
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function countByMonthAndEntreprise(int $entrepriseId): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 
                TO_CHAR(f.created_at, \'MM\') as month, 
                COUNT(f.id) as count
            FROM factures f
            WHERE f.id_entreprise_id = :entrepriseId
            GROUP BY month
            ORDER BY month
        ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['entrepriseId' => $entrepriseId]);

        return $resultSet->fetchAllAssociative();
    }
}
