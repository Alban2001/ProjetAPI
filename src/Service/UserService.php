<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SerializerInterface $serializer,
        private readonly ClientRepository $clientRepository,
        private readonly EntityManagerInterface $em,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly ValidatorInterface $validator,
        private readonly TagAwareCacheInterface $cachePool
    ) {
    }

    /**
     * Récupération de tout les users par un client
     * 
     * @param Client $client
     * @param int $page
     * @param int $limit
     * 
     * @return string
     */
    public function findAll(Client $client, int $page, int $limit): string
    {
        // Mise en place du cache pour garder en mémoire l'affichage de tous les utilisateurs
        $idCache = "getUserList-" . $client->getId() . "-" . $page . "-" . $limit;
        return $this->cachePool->get($idCache, function (ItemInterface $item) use ($client, $page, $limit) {
            $item->tag("usersCache");
            $userList = $this->userRepository->findAllWithPagination($client, $page, $limit);
            foreach ($userList as $user) {
                $user->setLinks([
                    "self" =>
                        ["href" => $this->urlGenerator->generate('users_list', ['id' => $user->getId()])],
                    "delete" =>
                        ["href" => $this->urlGenerator->generate('deleteUser', ['id' => $user->getId()])]
                ]);
            }
            return $this->serializer->serialize($userList, 'json', ["groups" => "getUsers"]);
        });

    }

    /**
     * Récupération des détails d'un utilisateur d'un client
     * 
     * @param User $user
     * 
     * @return string
     */
    public function find(User $user): string
    {
        // Mise en place du cache pour garder en mémoire l'affichage des détails d'un utilisateur
        $this->cachePool->invalidateTags(["usersCache"]);

        $idCache = "getUserDetails-" . $user->getId();
        return $this->cachePool->get($idCache, function (ItemInterface $item) use ($user) {
            $item->tag("userCache");
            $user->setLinks([
                "self" =>
                    ["href" => $this->urlGenerator->generate('users_list', ['id' => $user->getId()])],
                "delete" =>
                    ["href" => $this->urlGenerator->generate('deleteUser', ['id' => $user->getId()])]
            ]);
            return $this->serializer->serialize($user, 'json', ["groups" => "getUsers"]);
        });
    }

    /**
     * Création d'un utilisateur
     * 
     * @param Request $request
     * 
     * @return array
     */
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

        $arr["jsonUser"] = $this->serializer->serialize($user, 'json', ["groups" => "getUsers"]);
        $arr["location"] = $this->urlGenerator->generate('user_details', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return $arr;
    }

    /**
     * Suppression d'un utilisateur
     * 
     * @param User $user
     * 
     * @return void
     */
    public function delete(User $user)
    {
        $this->cachePool->invalidateTags(["usersCache"]);
        $this->em->remove($user);
        $this->em->flush();
    }
}