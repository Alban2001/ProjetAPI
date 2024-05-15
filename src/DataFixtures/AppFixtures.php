<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\ByteString;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $typeOS = ["IOS", "Android"];
        $memoryRam = [4, 6, 8, 10, 12, 14, 16, 18, 20];
        $memoryStore = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
        $model = ["Apple", "Samsung", "Google", "Nokia", "Xiaomi"];
        // Création automatique de 20 produits (portable)

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

        $manager->flush();
    }
}
