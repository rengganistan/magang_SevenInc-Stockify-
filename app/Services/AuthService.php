<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    // Mengembalikan deklarasi tipe data UserRepository sesuai instruksi awal
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Melakukan proses autentikasi login pengguna.
     */
public function login(string $email, string $password)
{
    $user = $this->userRepository->findByEmail($email);

    if (!$user) {
        return [
            'success' => false,
            'message' => 'Email tidak ditemukan.'
        ];
    }

    if (!Hash::check($password, $user->password)) {
        return [
            'success' => false,
            'message' => 'Password salah.'
        ];
    }

    Auth::login($user);

    return [
        'success' => true,
        'user' => $user
    ];
}


}
