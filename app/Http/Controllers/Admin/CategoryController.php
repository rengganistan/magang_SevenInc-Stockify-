<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): View
    {
        $categories = $this->categoryService->getCategories();
        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'        => 'required|max:100',
            'description' => 'nullable|max:255',
        ]);

        $this->categoryService->createCategory($validated);

        ActivityLog::record('Tambah Kategori', 'Kategori', $validated['nama']);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $category = $this->categoryService->getCategoryById($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'nama'        => 'required|max:100',
            'description' => 'nullable|max:255',
        ]);

        $this->categoryService->updateCategory($id, $validated);

        ActivityLog::record('Edit Kategori', 'Kategori', $validated['nama']);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $category = $this->categoryService->getCategoryById($id);
        $nama     = $category->nama;

        $this->categoryService->deleteCategory($id);

        ActivityLog::record('Hapus Kategori', 'Kategori', $nama);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
