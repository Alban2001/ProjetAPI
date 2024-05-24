<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(private readonly UserServiceInterface $userService)
    {
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
