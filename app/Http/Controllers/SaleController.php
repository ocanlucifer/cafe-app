<?php

// app/Http/Controllers/SaleController.php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'item'])->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::all();
        $items = Item::all();
        return view('sales.create', compact('customers', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer',
            'total_price' => 'required|numeric',
        ]);
        Sale::create($request->all());
        return redirect()->route('sales.index')->with('success', 'Sale transaction created successfully.');
    }

    public function edit(Sale $sale)
    {
        $customers = Customer::all();
        $items = Item::all();
        return view('sales.edit', compact('sale', 'customers', 'items'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer',
            'total_price' => 'required|numeric',
        ]);
        $sale->update($request->all());
        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}
