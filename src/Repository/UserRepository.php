<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Mapping\ClassMetadata;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Récupération de tous les utilisateurs avec un système de pagination
     *
     * @param Client $client 
     * @param int $page 
     * @param int $limit 
     *
     * @return array
     */
    public function findAllWithPagination(Client $client, int $page, int $limit): array
    {
        $maxPage = $limit;
        $firstPage = ($page - 1) * $maxPage;

        return $this->createQueryBuilder('u')
            ->select('u', 'c')
            ->join('u.client', 'c')
            ->andWhere('u.client = c.id')
            ->andWhere('u.client = :idClient')
            ->setParameter('idClient', $client->getId())
            ->orderBy('u.id', 'DESC')
            ->setFirstResult($firstPage)
            ->setMaxResults($maxPage)
            ->getQuery()
            ->getResult()
        ;
    }
}
