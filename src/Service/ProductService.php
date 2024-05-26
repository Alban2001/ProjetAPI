<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    // Récupération de tous les produits
    public function findAll(): string
    {
        $productList = $this->productRepository->findAll();

        return $this->serializer->serialize($productList, 'json');
    }

    // Récupération des détails d'un produit
    public function find(Product $product): string
    {
        return $this->serializer->serialize($product, 'json');
    }
}