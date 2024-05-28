<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SerializerInterface $serializer,
        private readonly ClientRepository $clientRepository,
        private readonly EntityManagerInterface $em,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly ValidatorInterface $validator
    ) {
    }

    // Récupération de tout les users par un client
    public function findAll(Client $client, int $page): string
    {
        $userList = $this->userRepository->findAllWithPagination($client, $page);

        return $this->serializer->serialize($userList, 'json', ['groups' => 'getUsers']);
    }

    // Récupération des détails d'un utilisateur d'un client
    public function find(User $user): string
    {
        return $this->serializer->serialize($user, 'json', ['groups' => 'getUsers']);
    }

    // Création d'un utilisateur
    public function create(Request $request): array
    {
        // Récupération des données
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

        // Vérification du format de données et gestion des erreurs
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $arr["jsonErrors"] = $this->serializer->serialize($errors, 'json');

            return $arr;
        }

        // Récupération de l'id client
        $content = $request->toArray();
        $idClient = $content["idClient"] ?? -1;
        $user->setClientId($this->clientRepository->find($idClient));

        $this->em->persist($user);
        $this->em->flush();

        $arr["jsonUser"] = $this->serializer->serialize($user, 'json', ['groups' => 'getUsers']);
        $arr["location"] = $this->urlGenerator->generate('user_details', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return $arr;
    }


    // Suppression d'un utilisateur
    public function delete(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}