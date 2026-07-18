<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SupplierService;

class ManagerSupplierController extends Controller
{
    protected SupplierService $service;

    public function __construct(SupplierService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $suppliers = $this->service->getSuppliers();
        return view('manager.suppliers.index', compact('suppliers'));
    }
}
