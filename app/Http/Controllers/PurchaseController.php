<?php

namespace App\Http\Controllers;

use App\Models\PurchaseHeader;
use App\Models\Vendor;
use App\Models\Item;
use App\Models\StockCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Tampilkan daftar pembelian.
     */
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'transaction_number');  // Default sorting berdasarkan transaction_number
        $order = $request->get('order', 'asc');  // Default ascending
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10); // Default to 10 per page
        $fromDate = $request->input('from_date', now()->toDateString());
        $toDate = $request->input('to_date', now()->toDateString());

        $purchases = PurchaseHeader::query()
                    ->when($search, function ($query, $search) {
                        return $query->where('transaction_number', 'like', "%$search%")
                                    ->orWhereHas('vendor', function ($query) use ($search) {
                                        $query->where('name', 'like', "%$search%");
                                    });
                    })
                    ->with(['vendor', 'details.item']);

        // Handle sorting logic
        if ($sortBy === 'vendor_name') {
            // If sorting by vendor name, join with vendors table and order by vendor name
            $purchases->join('vendors', 'purchase_headers.vendor_id', '=', 'vendors.id')
                ->select('purchase_headers.*', 'vendors.name as vendor_name')
                ->orderBy('vendors.name', $order);
        } else {
            // Otherwise, use the normal sortBy (e.g., 'name', 'price', etc.)
            $purchases->select('purchase_headers.*')
                ->orderBy($sortBy, $order);
        }

        if ($fromDate && $toDate) {
            $purchases->whereBetween('purchase_headers.created_at', [
                $fromDate . ' 00:00:00',
                $toDate . ' 23:59:59'
            ]);
        } elseif ($fromDate) {
            $purchases->whereDate('purchase_headers.created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $purchases->whereDate('purchase_headers.created_at', '<=', $toDate);
        }

        $result = $purchases->paginate($perPage);

        // If the request is an AJAX request, return the partial view with the table
        if ($request->ajax()) {
            return view('purchases.table', compact('result', 'search', 'perPage', 'sortBy', 'order', 'fromDate', 'toDate'));
        }

        return view('purchases.index', compact('result', 'search', 'perPage', 'sortBy', 'order', 'fromDate', 'toDate'));
    }

    /**
     * Form untuk membuat pembelian baru.
     */
    public function create()
    {
        $vendors = Vendor::where('Active',1)->get();
        $items = Item::where('Active',1)->get();
        return view('purchases.create', compact('vendors', 'items'));
    }

    /**
     * Simpan pembelian baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Create purchase header
            $purchaseHeader = PurchaseHeader::create([
                'vendor_id' => $validatedData['vendor_id'],
                'purchase_date' => $validatedData['purchase_date'],
                'total_amount' => $validatedData['total_amount'],
            ]);

            // Add purchase details
            foreach ($validatedData['items'] as $item) {
                $purchaseHeader->details()->create([
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price'],
                ]);
                $items = Item::find($item['item_id']);
                $stock_card = StockCard::Create([
                    'item_id'               => $item['item_id'],
                    'transaction_number'    => $purchaseHeader->transaction_number,
                    'qty_begin'             => $items->stock,
                    'qty_in'                => $item['quantity'],
                    'qty_out'               => 0,
                    'qty_end'               => $items->stock + $item['quantity']
                ]);
                $items->Update([
                    'stock' => $stock_card->qty_end,
                ]);
            }

            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Purchase created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create purchase: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form untuk mengedit pembelian.
     */
    public function edit(PurchaseHeader $purchase)
    {
        $vendors = Vendor::where('Active',1)->get();
        $items = Item::where('Active',1)->get();
        return view('purchases.edit', compact('purchase', 'vendors', 'items'));
    }

    /**
     * Update pembelian.
     */
    public function update(Request $request, PurchaseHeader $purchase)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Update purchase header
            $purchase->update([
                'vendor_id' => $validated['vendor_id'],
                'purchase_date' => $validated['purchase_date'],
                'total_amount' => $validated['total_amount'],
            ]);

            //kembalikan stock nya dulu
            foreach($purchase->details as $detail){
                $itemId = $detail->item_id;
                $findItem = Item::find($itemId);
                $findItem->Update([
                    'stock' => $findItem->stock - $detail->quantity,
                ]);
            }
            // Remove old purchase details and add new ones
            $purchase->details()->delete();
            //hapus data di stock_card
            StockCard::where('transaction_number',$purchase->transaction_number)->delete();

            foreach ($validated['items'] as $item) {
                $purchase->details()->create([
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price'],
                ]);

                $items = Item::find($item['item_id']);
                $stock_card = StockCard::Create([
                    'item_id'               => $item['item_id'],
                    'transaction_number'    => $purchase->transaction_number,
                    'qty_begin'             => $items->stock,
                    'qty_in'                => $item['quantity'],
                    'qty_out'               => 0,
                    'qty_end'               => $items->stock + $item['quantity']
                ]);

                $items->Update([
                    'stock' => $stock_card->qty_end,
                ]);
            }

            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update purchase: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail pembelian tertentu.
     */
    public function show(PurchaseHeader $purchase)
    {
        $purchase->load('vendor', 'details.item');
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Hapus pembelian.
     */
    public function destroy(PurchaseHeader $purchase)
    {
        try {
            //kembalikan stock nya dulu
            foreach($purchase->details as $detail){
                $itemId = $detail->item_id;
                $findItem = Item::find($itemId);
                $findItem->Update([
                    'stock' => $findItem->stock - $detail->quantity,
                ]);
            }
            //hapus data di stock_card
            StockCard::where('transaction_number',$purchase->transaction_number)->delete();

            $purchase->delete();
            return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete purchase: ' . $e->getMessage());
        }
    }
}
