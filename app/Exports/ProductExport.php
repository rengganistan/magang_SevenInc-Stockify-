<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with([
            'category',
            'supplier',
            'attributes'
        ])->get()->map(function ($product) {

            $attributes = collect($product->attributes)
                ->map(function ($attribute) {
                    return $attribute->name . ':' . $attribute->value;
                })
                ->implode(', ');

            return [

                'kode'          => $product->kode,

                'nama'          => $product->nama,

                'category_id'   => $product->category_id,

                'supplier_id'   => $product->supplier_id,

                'atribut_produk'=> $attributes,

                'stok'          => $product->stok,

                'stok_minimum'  => $product->stok_minimum,

                'satuan'        => $product->satuan,

                'harga_beli'    => $product->harga_beli,

                'harga_jual'    => $product->harga_jual,

                'deskripsi'     => $product->deskripsi,

            ];
        });
    }

    public function headings(): array
{
    return [

        'kode',

        'nama',

        'category_id',

        'supplier_id',

        'atribut_produk',

        'stok',

        'stok_minimum',

        'satuan',

        'harga_beli',

        'harga_jual',

        'deskripsi',

    ];
}
    }

