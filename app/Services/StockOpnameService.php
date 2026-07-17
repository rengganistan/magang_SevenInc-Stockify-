<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Repositories\StockOpnameRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockOpnameService
{
    protected StockOpnameRepository $repository;

    public function __construct(StockOpnameRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN OPNAME BARU
    | - Buat header opname (status Draft)
    | - Simpan setiap item beserta selisihnya
    |--------------------------------------------------------------------------
    */
    public function store(array $data, array $items): void
    {
        DB::transaction(function () use ($data, $items) {

            $opname = $this->repository->create([
                'user_id' => Auth::id(),
                'tanggal' => $data['tanggal'],
                'catatan' => $data['catatan'] ?? null,
                'status'  => 'Draft',
            ]);

            foreach ($items as $productId => $stokFisik) {

                $product   = Product::findOrFail($productId);
                $stokFisik = (int) $stokFisik;
                $selisih   = $stokFisik - $product->stok;

                $this->repository->createItem([
                    'stock_opname_id' => $opname->id,
                    'product_id'      => $productId,
                    'stok_sistem'     => $product->stok,
                    'stok_fisik'      => $stokFisik,
                    'selisih'         => $selisih,
                ]);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SELESAIKAN OPNAME
    | - Update stok produk ke stok fisik
    | - Catat transaksi penyesuaian
    | - Ubah status jadi Selesai
    |--------------------------------------------------------------------------
    */
    public function selesaikan($id): void
    {
        DB::transaction(function () use ($id) {

            $opname = $this->repository->findById($id);

            if ($opname->status === 'Selesai') {
                return;
            }

            foreach ($opname->items as $item) {

                $product = $item->product;

                if ($item->selisih === 0) {
                    continue;
                }

                // Catat transaksi penyesuaian
                StockTransaction::create([
                    'product_id' => $product->id,
                    'user_id'    => Auth::id(),
                    'type'       => $item->selisih > 0 ? 'Masuk' : 'Keluar',
                    'quantity'   => abs($item->selisih),
                    'date'       => $opname->tanggal,
                    'status'     => 'Diterima',
                    'notes'      => 'Penyesuaian stock opname #' . $opname->id,
                ]);

                // Update stok produk
                $product->stok = $item->stok_fisik;
                $product->save();
            }

            $this->repository->updateStatus($id, 'Selesai');
        });
    }

    public function delete($id): void
    {
        $opname = $this->repository->findById($id);

        if ($opname->status === 'Selesai') {
            abort(403, 'Opname yang sudah selesai tidak bisa dihapus.');
        }

        $this->repository->delete($id);
    }
}
