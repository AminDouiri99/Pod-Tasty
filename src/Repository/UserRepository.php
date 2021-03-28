<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findOrCreateFromGithubOauth(GithubResourceOwner $owner): User{
        $user= $this->createQueryBuilder('u')->where('u.githubId = :githubId ')->setParameters(
        ['githubId'=>$owner->getId()]
        )->getQuery()->getOneOrNullResult();
        if($user) {
            return $user;
        }
        $datearrondie = new \DateTime("2000-07-22 00:00:00");

        $userInfo=(new UserInfo())->setUserFirstName($owner->getNickname())->setUserLastName($owner->getNickname())->setUserGender("male")->setUserBirthDate($datearrondie);
        $user = (new User())->setGithubId($owner->getId())->setUserEmail($owner->getNickname())->setUserInfoId($userInfo)->setIsAdmin(false)->setDesactiveAccount(false);
        $em=$this->getEntityManager();
        $em->persist($user);
        $em->flush();
        return $user;
    }
}
