<?php

namespace App\Repository;

use App\Entity\Laboratoires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Laboratoires>
 *
 * @method Laboratoires|null find($id, $lockMode = null, $lockVersion = null)
 * @method Laboratoires|null findOneBy(array $criteria, array $orderBy = null)
 * @method Laboratoires[]    findAll()
 * @method Laboratoires[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LaboratoiresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Laboratoires::class);
    }

//    /**
//     * @return Laboratoires[] Returns an array of Laboratoires objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Laboratoires
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
