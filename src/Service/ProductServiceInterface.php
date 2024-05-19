<?php

namespace App\Service;

use App\Entity\Product;

interface ProductServiceInterface
{
    public function findAll();
    public function find(Product $product);
}