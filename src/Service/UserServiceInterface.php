<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface UserServiceInterface
{
    public function findAll(Client $client);
    public function create(Request $request);
    public function delete(User $user);
}