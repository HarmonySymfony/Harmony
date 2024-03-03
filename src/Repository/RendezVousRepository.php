<?php

namespace App\Repository;

use App\Entity\RendezVous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RendezVous>
 *
 * @method RendezVous|null find($id, $lockMode = null, $lockVersion = null)
 * @method RendezVous|null findOneBy(array $criteria, array $orderBy = null)
 * @method RendezVous[]    findAll()
 * @method RendezVous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendezVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }
    public function searchAndSort($searchTerm,  $sortField, $sortOrder)
{
    
    $query = $this->createQueryBuilder('r');
   
    if ($searchTerm) {
        $query->andWhere($query->expr()->orX(
            $query->expr()->like('r.nom', ':searchTerm'),
            $query->expr()->like('r.prenom', ':searchTerm'),
            $query->expr()->like('r.date', ':searchTerm'),
            $query->expr()->like('r.email', ':searchTerm')
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
//     * @return RendezVous[] Returns an array of RendezVous objects
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

//    public function findOneBySomeField($value): ?RendezVous
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
