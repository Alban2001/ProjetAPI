<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Service\UserServiceInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class UserController extends AbstractController
{
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    // Affichage de touts les utilisateurs d'un client
    #[Route('/users/client/{id}', name: 'users_list', methods: ['GET'])]
    public function getUserList(#[MapEntity(expr: 'repository.find(id)')] Client $client): JsonResponse
    {
        return new JsonResponse([
            json_decode($this->userService->findAll($client))
        ], Response::HTTP_OK);
    }

    // Affichage des détails d'un utilisateur d'un client
    #[Route('/users/{id}', name: 'user_details', methods: ['GET'])]
    public function getUserDetails(#[MapEntity(expr: 'repository.find(id)')] User $user): JsonResponse
    {
        return new JsonResponse($this->userService->find($user), Response::HTTP_OK, ['accept' => 'json'], true);
    }

    // Création d'un nouvel utilisateur
    #[Route('/users/', name: 'createUser', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $arr = $this->userService->create($request);

        return new JsonResponse($arr["jsonUser"], Response::HTTP_CREATED, ["Location" => $arr["location"]], true);
    }

    // Suppression d'un utilisateur
    #[Route('/users/{id}', name: 'deleteUser', methods: ['DELETE'])]
    public function deleteUser(User $user): JsonResponse
    {
        $this->userService->delete($user);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
