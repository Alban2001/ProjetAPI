<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly SerializerInterface $serializer,
        private readonly TagAwareCacheInterface $cachePool,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    // Récupération de tous les produits
    public function findAll(int $page, int $limit): string
    {
        $idCache = "getProductList-" . $page . "-" . $limit;
        return $this->cachePool->get($idCache, function (ItemInterface $item) use ($page, $limit) {
            $item->tag("productsCache");
            $productList = $this->productRepository->findAllWithPagination($page, $limit);
            foreach ($productList as $product) {
                $product->setLinks([
                    "self" =>
                        ["href" => $this->urlGenerator->generate('product_details', ['id' => $product->getId()])]
                ]);
            }
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

            $links = [
                "self" =>
                    ["href" => $this->urlGenerator->generate('product_details', ['id' => $product->getId()])]
            ];
            $product->setLinks($links);

            return $this->serializer->serialize($product, 'json');
        });
    }
}