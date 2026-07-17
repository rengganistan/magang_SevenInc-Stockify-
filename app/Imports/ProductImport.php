<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductAttribute;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    protected array $errors = [];

    public function model(array $row)
    {
        /*
        |--------------------------------------------------------------------------
        | Validasi category_id
        |--------------------------------------------------------------------------
        */

        if (empty($row['category_id'])) {

            $this->errors[] =
                "Category ID kosong pada produk {$row['kode']}";

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi supplier_id
        |--------------------------------------------------------------------------
        */

        if (empty($row['supplier_id'])) {

            $this->errors[] =
                "Supplier ID kosong pada produk {$row['kode']}";

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan atau Update Produk
        |--------------------------------------------------------------------------
        */

        $product = Product::updateOrCreate(

            [
                'kode' => trim($row['kode']),
            ],

            [
                'nama'          => trim($row['nama']),
                'category_id'   => $row['category_id'],
                'supplier_id'   => $row['supplier_id'],
                'stok'          => $row['stok'],
                'stok_minimum'  => $row['stok_minimum'],
                'satuan'        => $row['satuan'],
                'harga_beli'    => $row['harga_beli'],
                'harga_jual'    => $row['harga_jual'],
                'deskripsi'     => $row['deskripsi'] ?? null,
            ]

        );

        /*
        |--------------------------------------------------------------------------
        | Import atribut produk (opsional)
        |--------------------------------------------------------------------------
        */

        if (!empty($row['atribut_produk'])) {

            // hapus atribut lama
            $product->attributes()->delete();

            $items = explode(',', $row['atribut_produk']);

            foreach ($items as $item) {

                $pair = explode(':', $item);

                if (count($pair) == 2) {

                    ProductAttribute::create([

                        'product_id' => $product->id,
                        'name'       => trim($pair[0]),
                        'value'      => trim($pair[1]),

                    ]);

                }
            }
        }

        return $product;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
