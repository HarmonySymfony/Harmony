<?php

namespace App\Repository;

use App\Entity\CommentEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommentEvent>
 *
 * @method CommentEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentEvent[]    findAll()
 * @method CommentEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentEvent::class);
    }

//    /**
//     * @return CommentEvent[] Returns an array of CommentEvent objects
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

//    public function findOneBySomeField($value): ?CommentEvent
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
