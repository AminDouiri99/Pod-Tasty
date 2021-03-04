<?php

namespace App\Repository;

use App\Entity\PodcastReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PodcastReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method PodcastReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method PodcastReview[]    findAll()
 * @method PodcastReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PodcastReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PodcastReview::class);
    }

    // /**
    //  * @return PodcastReview[] Returns an array of PodcastReview objects
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
    public function findOneBySomeField($value): ?PodcastReview
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
