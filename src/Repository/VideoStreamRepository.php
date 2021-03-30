<?php

namespace App\Repository;

use App\Entity\VideoStream;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VideoStream|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoStream|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoStream[]    findAll()
 * @method VideoStream[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoStreamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoStream::class);
    }

    // /**
    //  * @return VideoStream[] Returns an array of VideoStream objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VideoStream
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
