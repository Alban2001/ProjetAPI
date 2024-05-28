<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // Récupération de tous les utilisateurs avec un système de pagination
    public function findAllWithPagination(Client $client, int $page): array
    {
        $maxPage = 10;
        $firstPage = ($page - 1) * $maxPage;

        return $this->createQueryBuilder('u')
            ->andWhere('u.client_id = :idClient')
            ->setParameter('idClient', $client->getId())
            ->orderBy('u.id', 'DESC')
            ->setFirstResult($firstPage)
            ->setMaxResults($maxPage)
            ->getQuery()
            ->getResult()
        ;
    }
    //    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
