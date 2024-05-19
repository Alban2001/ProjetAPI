<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductServiceInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    public function __construct(private readonly ProductServiceInterface $productService)
    {
    }

    // Affichage de tout les produits
    #[Route('/api/products', name: 'products_list', methods: ['GET'])]
    public function getProductList(): JsonResponse
    {
        return $this->productService->findAll();
    }

    // Affichage d'un produit en détail
    #[Route('/api/products/{id}', name: 'product_details', methods: ['GET'])]
    public function getProductDetails(#[MapEntity(expr: 'repository.find(id)')] Product $product): JsonResponse
    {
        return $this->productService->find($product);
    }
}
