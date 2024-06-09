<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Method Récupération de tous les produits avec un système de pagination
     *
     * @param int $page 
     * @param int $limit 
     *
     * @return array
     */
    public function findAllWithPagination(int $page, int $limit): array
    {
        $maxPage = $limit;
        $firstPage = ($page - 1) * $maxPage;

        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setFirstResult($firstPage)
            ->setMaxResults($maxPage)
            ->getQuery()
            ->getResult()
        ;
    }
}
