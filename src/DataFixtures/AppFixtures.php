<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\ByteString;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /* Création automatique de 20 produits (portable) */
        $typeOS = ["IOS", "Android"];
        $memoryRam = [4, 6, 8, 10, 12, 14, 16, 18, 20];
        $memoryStore = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
        $model = ["Apple", "Samsung", "Google", "Nokia", "Xiaomi"];

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName("Mobile_" . $i);
            $product->setNumSerie(ByteString::fromRandom(15, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'));
            $rndModel = random_int(1, 5);
            $product->setModel($model[$rndModel - 1]);
            $rndMemoryRam = random_int(1, 9);
            $product->setMemoryRam($memoryRam[$rndMemoryRam - 1]);
            $rndMemoryStore = random_int(1, 10);
            $product->setMemoryStore($memoryStore[$rndMemoryStore - 1]);
            $rndTypeOS = random_int(1, 2);
            $product->setTypeOS($typeOS[$rndTypeOS - 1]);
            $manager->persist($product);
        }

        /* Création d'un client */
        $client = new Client();
        $client->setName("Client 01");
        $client->setEmail("client01@gmail.com");
        $client->setRoles(["ROLE_ADMIN"]);
        $client->setPassword($this->userPasswordHasher->hashPassword($client, 'Client01?!')); // Client01?!
        $manager->persist($client);

        /* Création d'un client 2 */
        $client2 = new Client();
        $client2->setName("Client 02");
        $client2->setEmail("client02@gmail.com");
        $client2->setRoles(["ROLE_ADMIN"]);
        $client2->setPassword($this->userPasswordHasher->hashPassword($client2, 'Client02?!')); // Client02?!
        $manager->persist($client2);

        $manager->flush();
    }
}
