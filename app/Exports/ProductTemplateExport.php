<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return new Collection([
            [
                'PRD001',
                'Contoh Produk',
                1,
                1,
                100,
                10,
                'pcs',
                50000,
                70000,
                'Deskripsi Produk'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'kode',
            'nama',
            'category_id',
            'supplier_id',
            'stok',
            'stok_minimum',
            'satuan',
            'harga_beli',
            'harga_jual',
            'deskripsi'
        ];
    }
}
