<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Service\UserServiceInterface;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

#[Route('/api')]
class UserController extends AbstractController
{
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    #[OA\Response(
        response: 200,
        description: 'Affichage de tous les utilisateurs',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['getUsers']))
        )
    )]

    #[OA\Response(
        response: 403,
        description: 'Vous n\'avez pas les droits pour consulter la liste des utilisateurs'
    )]

    #[OA\Response(
        response: 404,
        description: 'Client introuvable'
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

    #[OA\Tag(name: 'Utilisateurs')]

    #[Route('/users/client/{id}', name: 'users_list', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', statusCode: 403, message: 'Vous n\'avez pas les droits pour consulter la liste des utilisateurs !')]
    /*
     * Méthode permettant de retourner tous les utilisateurs d'un client
     * 
     * @param Request $request
     * @param Client $client
     * @return JsonResponse
     */
    public function getUserList(#[MapEntity(expr: 'repository.find(id)')] Client $client = null, Request $request): JsonResponse
    {
        /* On vérifie que le client existe dans la base de données */
        if ($client === null) {
            return new JsonResponse([
                "code" => Response::HTTP_NOT_FOUND,
                "message" => "Ce compte n'existe pas !"
            ], Response::HTTP_NOT_FOUND);
        }

        /* On vérifie si le client connecté est bien le client de cet utilisateur */
        if ($this->getUser()->getUserIdentifier() == $client->getUserIdentifier()) {
            /* Récupération du numéro de page dans les paramètres + page 1 par défaut */
            $page = $request->get('page', 1);
            $limit = $request->get('limit', 10);

            return new JsonResponse(json_decode($this->userService->findAll($client, $page, $limit)), Response::HTTP_OK);
        } else {
            return new JsonResponse([
                "code" => Response::HTTP_FORBIDDEN,
                "message" => "Vous n'avez pas les droits pour consulter la liste de ces utilisateurs !"
            ], Response::HTTP_FORBIDDEN);
        }
    }

    #[OA\Response(
        response: 200,
        description: 'Affichage d\'un utilisateur',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['getUsers']))
        )
    )]

    #[OA\Response(
        response: 403,
        description: 'Vous n\'avez pas les droits pour consulter les détails de cet utilisateur'
    )]

    #[OA\Response(
        response: 404,
        description: 'Utilisateur introuvable'
    )]

    #[OA\Tag(name: 'Utilisateurs')]

    #[Route('/users/{id}', name: 'user_details', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', statusCode: 403, message: 'Vous n\'avez pas les droits pour consulter les détails de cet utilisateur !')]
    /*
     * Méthode permettant de retourner un utilisateur d'un client
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function getUserDetails(#[MapEntity(expr: 'repository.find(id)')] User $user = null): JsonResponse
    {
        /* On vérifie que l'utilisateur existe dans la base de données */
        if ($user === null) {
            return new JsonResponse([
                "code" => Response::HTTP_NOT_FOUND,
                "message" => "Cet utilisateur n'existe pas !"
            ], Response::HTTP_NOT_FOUND);
        }

        // On vérifie si le client connecté est bien le client de la liste de ses utilisateurs
        if ($this->getUser()->getUserIdentifier() == $user->getClientId()->getUserIdentifier()) {
            return new JsonResponse($this->userService->find($user), Response::HTTP_OK, ['accept' => 'json'], true);
        } else {
            return new JsonResponse([
                "code" => Response::HTTP_FORBIDDEN,
                "message" => "Vous n'avez pas les droits pour consulter les détails de cet utilisateur !"
            ], Response::HTTP_FORBIDDEN);
        }
    }


    #[OA\RequestBody(
        request: "User",
        description: "Création d'un nouvel utilisateur",
        required: true,
        content: new OA\JsonContent(
            type: User::class,
            example: [
                "name" => "name",
                "first_name" => "first_name",
                "email" => "example@mail.fr",
                "idClient" => 0
            ]
        )
    )]

    #[OA\Response(
        response: 201,
        description: 'Création d\'un utilisateur créé avec succès',
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['getUsers']))
    )]

    #[OA\Response(
        response: 403,
        description: 'Vous n\'avez pas les droits pour ajouter un nouvel utilisateur'
    )]

    #[OA\Tag(name: 'Utilisateurs')]

    #[Route('/users', name: 'createUser', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', statusCode: 403, message: 'Vous n\'avez pas les droits pour ajouter un nouvel utilisateur !')]
    /*
     * Méthode permettant de créer un nouvel utilisateur
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request): JsonResponse
    {
        $arr = $this->userService->create($request);

        /* Si la requête est un succès alors */
        if (array_key_exists("jsonUser", $arr)) {
            return new JsonResponse($arr["jsonUser"], Response::HTTP_CREATED, ["Location" => $arr["location"]], true);
        } else {
            return new JsonResponse($arr["jsonErrors"], Response::HTTP_BAD_REQUEST, [], true);
        }
    }

    #[OA\Response(
        response: 204,
        description: 'Suppression d\'un utilisateur créé avec succès'
    )]

    #[OA\Response(
        response: 403,
        description: 'Vous n\'avez pas les droits pour supprimer cet utilisateur'
    )]

    #[OA\Response(
        response: 404,
        description: 'Utilisateur introuvable'
    )]

    #[OA\Tag(name: 'Utilisateurs')]

    #[Route('/users/{id}', name: 'deleteUser', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', statusCode: 403, message: 'Vous n\'avez pas les droits pour supprimer cet utilisateur !')]
    /*
     * Méthode permettant de supprimer un utilisateur
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function deleteUser(User $user = null): JsonResponse
    {
        /* On vérifie que l'utilisateur existe dans la base de données */
        if ($user === null) {
            return new JsonResponse([
                "code" => Response::HTTP_NOT_FOUND,
                "message" => "Cet utilisateur n'existe pas !"
            ], Response::HTTP_NOT_FOUND);
        }

        /* On vérifie si le client connecté est bien le client de cet utilisateur */
        if ($this->getUser()->getUserIdentifier() == $user->getClientId()->getUserIdentifier()) {
            $this->userService->delete($user);

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } else {
            return new JsonResponse([
                "code" => Response::HTTP_FORBIDDEN,
                "message" => "Vous n'avez pas les droits pour supprimer cet utilisateur !"
            ], Response::HTTP_FORBIDDEN);
        }
    }
}
