<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\StockTransactionService;

class StockTransactionController extends Controller
{
    protected StockTransactionService $service;

    public function __construct(
        StockTransactionService $service
    )
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function incoming()
{
    $transactions = $this->service
        ->getIncomingTransactions();

    return view(
        'stock-transactions.incoming',
        compact('transactions')
    );
}

public function outgoing()
{
    $transactions = $this->service
        ->getOutgoingTransactions();

    return view(
        'stock-transactions.outgoing',
        compact('transactions')
    );
}

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $products = Product::orderBy('nama')->get();

        $suppliers = Supplier::orderBy('nama')->get();

        $type = request('type', 'Masuk');

        return view(
            'stock-transactions.create',
            compact('products', 'suppliers', 'type')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'product_id' => 'required|exists:products,id',

            'supplier_id' => 'nullable|exists:suppliers,id',

            'type' => 'required|in:Masuk,Keluar',

            'quantity' => 'required|integer|min:1',

            'date' => 'required|date',

            'status' => 'required',

            'notes' => 'nullable'

        ]);

        $validated['user_id'] = Auth::id();

        $this->service->createTransaction($validated);

        $product = Product::find($validated['product_id']);
        $action  = $validated['type'] === 'Masuk' ? 'Barang Masuk' : 'Barang Keluar';
        ActivityLog::record($action, 'Transaksi', $product->nama ?? '-', 'Qty: ' . $validated['quantity']);

        $route = $validated['type'] === 'Masuk'
            ? 'transactions.incoming'
            : 'transactions.outgoing';

        return redirect()
            ->route($route)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $transaction = $this->service
            ->getTransactionById($id);

        $products = Product::orderBy('nama')
            ->get();

        return redirect()
    ->route('transactions.incoming');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    )
    {
        $validated = $request->validate([

            'product_id' => 'required',

            'type' => 'required',

            'quantity' => 'required|integer',

            'date' => 'required|date',

            'status' => 'required',

            'notes' => 'nullable'

        ]);

        $this->service
            ->updateTransaction(
                $id,
                $validated
            );

        return redirect()
    ->route('transactions.incoming');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $this->service
            ->deleteTransaction($id);

        return redirect()
            ->route('transactions.incoming')
            ->with(
                'success',
                'Transaksi berhasil dihapus.'
            );
    }


    }


