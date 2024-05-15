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

    /**
     * Recherche les clients en fonction de id, nom, prénom
     * @param array $criteria Les critères de recherche
     * @return array La liste des clients correspondant aux critères
     */
    public function search(array $criteria): array
    {
        $queryBuilder = $this->createQueryBuilder('c');

        // Recherche par numéro ID
        if (!empty($LesCriteres['id'])) {
            $queryBuilder->andWhere('c.id = :id')
                ->setParameter('id', $LesCriteres['id']);
        }

        // Recherche par nom
        if (!empty($LesCriteres['nom'])) {
            $queryBuilder->andWhere('c.nom LIKE :nom')
                ->setParameter('nom', '%'.$LesCriteres['nom'].'%');
        }

        // Recherche par prénom
        if (!empty($LesCriteres['prenom'])) {
            $queryBuilder->andWhere('c.prenom LIKE :prenom')
                ->setParameter('prenom', '%'.$LesCriteres['prenom'].'%');
        }

        return $queryBuilder->getQuery()->getResult();
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
