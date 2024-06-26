<?php

namespace App\Repository;

use App\Entity\Analyse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Analyse>
 *
 * @method Analyse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Analyse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Analyse[]    findAll()
 * @method Analyse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnalyseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Analyse::class);
    }



    public function findLaboById($id): ?string
{
    // Récupérez l'entité Analyse correspondant à l'ID donné
    $analyse = $this->find($id);

    // Vérifiez si une entité Analyse a été trouvée
    if (!$analyse) {
        return null;
    }

    // Renvoie le nom de l'entité Analyse
    return $analyse->getLaboratoire()->getId();
}

//    /**
//     * @return Analyse[] Returns an array of Analyse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Analyse
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
