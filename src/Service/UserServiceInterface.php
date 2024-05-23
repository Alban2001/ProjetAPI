<?php

namespace App\Service;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;

interface UserServiceInterface
{
    public function createUser(Request $request);
}