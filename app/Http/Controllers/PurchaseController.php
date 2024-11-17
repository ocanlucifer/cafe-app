<?php

// app/Http/Controllers/PurchaseController.php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Vendor;
use App\Models\Item;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['vendor', 'item'])->get();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $vendors = Vendor::all();
        $items = Item::all();
        return view('purchases.create', compact('vendors', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer',
            'total_price' => 'required|numeric',
        ]);
        Purchase::create($request->all());
        return redirect()->route('purchases.index')->with('success', 'Purchase transaction created successfully.');
    }

    public function edit(Purchase $purchase)
    {
        $vendors = Vendor::all();
        $items = Item::all();
        return view('purchases.edit', compact('purchase', 'vendors', 'items'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer',
            'total_price' => 'required|numeric',
        ]);
        $purchase->update($request->all());
        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
