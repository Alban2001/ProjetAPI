<?php

namespace App\Service;

use App\Entity\Product;

interface ProductServiceInterface
{
    public function findAll(int $page);
    public function find(Product $product);
}