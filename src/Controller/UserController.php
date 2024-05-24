<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Service\UserServiceInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    // Affichage de touts les utilisateurs d'un client
    #[Route('/api/users/{idClient}', name: 'users_list', methods: ['GET'])]
    public function getUserList(#[MapEntity(expr: 'repository.find(idClient)')] Client $client): JsonResponse
    {
        return $this->userService->findAll($client);
    }

    // Création d'un nouvel utilisateur
    #[Route('/api/users/create', name: 'createUser', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        return $this->userService->create($request);
    }

    // Suppression d'un utilisateur
    #[Route('/api/users/delete/{id}', name: 'deleteUser', methods: ['DELETE'])]
    public function deleteUser(User $user): JsonResponse
    {
        return $this->userService->delete($user);
    }
}
