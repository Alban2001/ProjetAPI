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
    public function __construct(private readonly ProductRepository $productRepository, private readonly SerializerInterface $serializer)
    {
    }

    // Récupération de tous les produits
    public function findAll(): JsonResponse
    {
        $productList = $this->productRepository->findAll();
        $jsonProductList = $this->serializer->serialize($productList, 'json');

        return new JsonResponse([
            json_decode($jsonProductList),
            Response::HTTP_OK,
        ]);
    }

    // Récupération des détails d'un produit
    public function find(Product $product): JsonResponse
    {
        $jsonBook = $this->serializer->serialize($product, 'json');

        return new JsonResponse($jsonBook, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}