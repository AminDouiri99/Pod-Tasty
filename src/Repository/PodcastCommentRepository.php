<?php

namespace App\Repository;

use App\Entity\PodcastComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PodcastComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method PodcastComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method PodcastComment[]    findAll()
 * @method PodcastComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PodcastCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PodcastComment::class);
    }

    // /**
    //  * @return PodcastComment[] Returns an array of PodcastComment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PodcastComment
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
