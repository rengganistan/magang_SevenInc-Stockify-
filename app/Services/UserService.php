<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Mengambil seluruh user.
     */
    public function getUsers()
    {
        return $this->userRepository->getAll();
    }

    public function createUser(array $data)
{
    $data['password'] = Hash::make($data['password']);

    return $this->userRepository->create($data);
}
}
