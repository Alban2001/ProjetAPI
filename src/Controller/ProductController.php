<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductServiceInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

#[Route('/api')]
class ProductController extends AbstractController
{
    public function __construct(private readonly ProductServiceInterface $productService)
    {
    }

    #[OA\Response(
        response: 200,
        description: 'Affichage de tous les produits',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Product::class))
        )
    )]

    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'Le numéro de page',
        schema: new OA\Schema(type: 'int')
    )]

    #[OA\Parameter(
        name: 'limit',
        in: 'query',
        description: 'Le nombre d\'élements par page',
        schema: new OA\Schema(type: 'int')
    )]

    #[OA\Tag(name: 'Produits')]

    #[Route('/products', name: 'products_list', methods: ['GET'])]

    /*
     * Méthode permettant de retourner tous les produits
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getProductList(Request $request): JsonResponse
    {
        /* Récupération du numéro de page et limit dans les paramètres + (page 1 et limit 10 par défaut) */
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        return new JsonResponse(
            [json_decode($this->productService->findAll($page, $limit))],
            Response::HTTP_OK,
        );
    }

    #[OA\Response(
        response: 200,
        description: 'Affichage du détail d\'un produit',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Product::class))
        )
    )]

    #[OA\Response(
        response: 404,
        description: 'Produit introuvable'
    )]

    #[OA\Tag(name: 'Produits')]

    #[Route('/products/{id}', name: 'product_details', methods: ['GET'])]

    /*
     * Méthode permettant de retourner le détail d'un produit
     * 
     * @param Product $product
     * @return JsonResponse
     */
    public function getProductDetails(#[MapEntity(expr: 'repository.find(id)')] Product $product = null): JsonResponse
    {
        /* On vérifie que le produit existe dans la base de données */
        if ($product === null) {
            return new JsonResponse([
                "code" => Response::HTTP_NOT_FOUND,
                "message" => "Ce produit n'existe pas !"
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($this->productService->find($product), Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
