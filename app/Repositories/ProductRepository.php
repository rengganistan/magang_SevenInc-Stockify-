<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductRepository
{
    /**
     * Ambil semua produk beserta kategori, dengan opsional filter pencarian.
     */
    public function getAll(?string $search = null, ?string $stokFilter = null)
    {
        $query = Product::with([
            'category',
            'supplier',
            'attributes'
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        if ($stokFilter === 'habis') {
            $query->where('stok', 0);
        } elseif ($stokFilter === 'menipis') {
            $query->whereColumn('stok', '<=', 'stok_minimum')->where('stok', '>', 0);
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    /**
     * Cari produk berdasarkan ID.
     */
    public function findById($id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Simpan produk baru.
     */
    public function create(array $data)
    {
        return Product::create($data);
    }

    /**
     * Update produk.
     */
    public function update($id, array $data)
    {
        $product = Product::findOrFail($id);

        // Hapus gambar lama jika ada gambar baru
        if (isset($data['gambar']) && $product->gambar) {

            Storage::disk('public')->delete($product->gambar);

        }

        $product->update($data);

        return $product;
    }

    /**
     * Hapus produk.
     */
    public function delete($id)
    {
        $product = Product::findOrFail($id);

        // Hapus gambar dari storage
        if ($product->gambar) {

            Storage::disk('public')->delete($product->gambar);

        }

        return $product->delete();
    }
}
