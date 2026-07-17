<?php

namespace App\Http\Controllers;

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

    /**
     * Menampilkan daftar category
     */
    public function index(): View
    {
        $categories = $this->categoryService->getCategories();

        return view('categories.index', compact('categories'));
    }

    /**
     * Form tambah category
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Simpan category
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'=>'required|max:100',
            'description'=>'nullable|max:255'
        ]);

        $this->categoryService->createCategory($validated);

        return redirect()
                ->route('categories.index')
                ->with('success','Category berhasil ditambahkan.');
    }

    /**
     * Form edit
     */
    public function edit(int $id): View
    {
        $category = $this->categoryService->getCategoryById($id);

        return view('categories.edit', compact('category'));
    }

    /**
     * Update category
     */
    public function update(Request $request,int $id): RedirectResponse
    {
        $validated = $request->validate([
            'nama'=>'required|max:100',
            'description'=>'nullable|max:255'
        ]);

        $this->categoryService->updateCategory($id,$validated);

        return redirect()
                ->route('categories.index')
                ->with('success','Category berhasil diupdate.');
    }

    /**
     * Delete
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->categoryService->deleteCategory($id);

        return redirect()
                ->route('categories.index')
                ->with('success','Category berhasil dihapus.');
    }
}
