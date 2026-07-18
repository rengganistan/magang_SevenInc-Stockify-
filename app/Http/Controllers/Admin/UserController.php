<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getUsers();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(int $id): View
    {
        $user = $this->userService->getUserById($id);
        return view('users.edit', compact('user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,manager,staff',
        ]);

        $this->userService->createUser($validated);

        ActivityLog::record('Tambah User', 'User', $validated['name'], 'Role: ' . $validated['role']);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'nullable|min:6',
            'role'     => 'required|in:admin,manager,staff',
        ]);

        $this->userService->updateUser($id, $validated);

        ActivityLog::record('Edit User', 'User', $validated['name'], 'Role: ' . $validated['role']);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = $this->userService->getUserById($id);
        $nama = $user->name;

        $this->userService->deleteUser($id);

        ActivityLog::record('Hapus User', 'User', $nama);

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
