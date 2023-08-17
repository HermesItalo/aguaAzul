<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
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


    /**
    * @return User[] Returns an array of User objects
     */
   public function findByCupom($value): array
   {
       return $this->createQueryBuilder('u')
           ->select('u.cupom')
           ->andWhere('u.email = :val')
           ->setParameter('val', $value)
           ->setMaxResults(1)
           ->getQuery()
           ->getResult()
       ;
    }


    public function upSaldo($email, $value)
    {
       return $this->createQueryBuilder('u')
           ->update()
           ->set('u.meuSaldo', ':value')
           ->andWhere('u.email = :email')
           ->setParameter('value', $value)
           ->setParameter('email', $email)
           ->getQuery()
           ->getResult()
        ;
    }
}
