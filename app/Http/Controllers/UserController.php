<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{

protected UserService $userService;/**


     * Menampilkan halaman daftar user.
     */
    public function index()
{
    $users = $this->userService->getUsers();

    return view('users.index', compact('users'));
}

    /**
     * Menampilkan form tambah user.
     */
    public function create()
{
    return view('users.create');
}


    /**
     * Menampilkan detail user.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(string $id): View
    {
        return view('users.edit');
    }

    /**
     * Update user.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Hapus user.
     */
    public function destroy(string $id)
    {
        //
    }
    public function __construct(UserService $userService)
{
    $this->userService = $userService;
}

public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role' => 'required|in:admin,manager,staff',
    ]);

    $this->userService->createUser($validated);

    return redirect()
        ->route('users.index')
        ->with('success', 'User berhasil ditambahkan.');
}
}
