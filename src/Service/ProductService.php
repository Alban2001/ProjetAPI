<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly SerializerInterface $serializer,
        private readonly TagAwareCacheInterface $cachePool
    ) {
    }

    // Récupération de tous les produits
    public function findAll(int $page): string
    {
        $idCache = "getProductList-" . $page;
        return $this->cachePool->get($idCache, function (ItemInterface $item) use ($page) {
            $item->tag("productsCache");
            $productList = $this->productRepository->findAllWithPagination($page);
            return $this->serializer->serialize($productList, 'json');
        });
    }

    // Récupération des détails d'un produit
    public function find(Product $product): string
    {
        $this->cachePool->invalidateTags(["productsCache"]);

        $idCache = "getProductDetails-" . $product->getId();
        return $this->cachePool->get($idCache, function (ItemInterface $item) use ($product) {
            $item->tag("productCache");
            return $this->serializer->serialize($product, 'json');
        });
    }
}