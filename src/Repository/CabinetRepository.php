<?php

namespace App\Repository;

use App\Entity\Cabinet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cabinet>
 *
 * @method Cabinet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cabinet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cabinet[]    findAll()
 * @method Cabinet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CabinetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cabinet::class);
    }
    public function searchAndSort($searchTerm,  $sortField, $sortOrder)
    {
        
        $query = $this->createQueryBuilder('r');
       
        if ($searchTerm) {
            $query->andWhere($query->expr()->orX(
                $query->expr()->like('r.adress', ':searchTerm'),
                $query->expr()->like('r.horaires', ':searchTerm'), // Utilisez 'horaires' au lieu de 'Horaires'
                $query->expr()->like('r.email', ':searchTerm'),  // Utilisez 'email' au lieu de 'Email'
            ))
                  ->setParameter('searchTerm', '%'.$searchTerm.'%');
                  
        }  // Logic for search
    
        if ($sortField) {
            // Assuming $sortField contains the field name to sort by
            // Assuming $sortOrder contains the sorting order (ASC or DESC)
            $query->orderBy('r.'.$sortField, $sortOrder);
        }  // Logic for sorting
        return $query->getQuery()->getResult();
    }
    
 
//    /**
//     * @return Cabinet[] Returns an array of Cabinet objects
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

//    public function findOneBySomeField($value): ?Cabinet
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
