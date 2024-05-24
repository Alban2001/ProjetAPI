<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface UserServiceInterface
{
    public function create(Request $request);
    public function delete(User $user);
}