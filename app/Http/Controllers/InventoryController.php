<?php

// app/Http/Controllers/InventoryController.php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Item;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::with('item')->get();
        return view('inventories.index', compact('inventories'));
    }

    public function create()
    {
        $items = Item::all();
        return view('inventories.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer',
        ]);
        Inventory::create($request->all());
        return redirect()->route('inventories.index')->with('success', 'Inventory updated successfully.');
    }

    public function edit(Inventory $inventory)
    {
        $items = Item::all();
        return view('inventories.edit', compact('inventory', 'items'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer',
        ]);
        $inventory->update($request->all());
        return redirect()->route('inventories.index')->with('success', 'Inventory updated successfully.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventories.index')->with('success', 'Inventory deleted successfully.');
    }
}
