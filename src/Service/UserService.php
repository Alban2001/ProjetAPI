<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserService implements UserServiceInterface
{
    public function __construct(private readonly UserRepository $userRepository, private readonly SerializerInterface $serializer, private readonly ClientRepository $clientRepository, private readonly EntityManagerInterface $em, private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    // Récupération de tous les produits
    public function createUser(Request $request): JsonResponse
    {
        // Récupération des données
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

        // Récupération de l'id client
        $content = $request->toArray();
        $idClient = $content["idClient"] ?? -1;
        $user->setClientId($this->clientRepository->find($idClient));

        $this->em->persist($user);
        $this->em->flush();

        $jsonUser = $this->serializer->serialize($user, 'json', ['groups' => 'getUsers']);

        // $location = $this->urlGenerator->generate('user_details', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        // return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);

        return new JsonResponse($jsonUser, Response::HTTP_CREATED, [], true);
    }
}