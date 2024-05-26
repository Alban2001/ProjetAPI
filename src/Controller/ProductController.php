<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductServiceInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ProductController extends AbstractController
{
    public function __construct(private readonly ProductServiceInterface $productService)
    {
    }

    // Affichage de tout les produits
    #[Route('/products', name: 'products_list', methods: ['GET'])]
    public function getProductList(): JsonResponse
    {
        return new JsonResponse([
            json_decode($this->productService->findAll()),
            Response::HTTP_OK,
        ]);
    }

    // Affichage d'un produit en détail
    #[Route('/products/{id}', name: 'product_details', methods: ['GET'])]
    public function getProductDetails(#[MapEntity(expr: 'repository.find(id)')] Product $product): JsonResponse
    {
        return new JsonResponse($this->productService->find($product), Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
