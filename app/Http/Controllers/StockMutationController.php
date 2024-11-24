<?php

namespace App\Http\Controllers;

use App\Models\StockCard;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMutationController extends Controller
{
    public function index()
    {
        $items = Item::orderBy('name')->get(); // Semua item untuk dropdown filter
        return view('stock-mutations.index', compact('items'));
    }

    public function fetchData(Request $request)
    {
        // // Ambil filter dari request
        // $dateFrom = $request->input('date_from');
        // $dateTo = $request->input('date_to');
        // $itemName = $request->input('item_name');

        // // Query awal untuk StockCard
        // $query = StockCard::query()
        //     ->join('items', 'stock_cards.item_id', '=', 'items.id')
        //     ->select(
        //         'items.name as item_name',
        //         'stock_cards.item_id',
        //         'stock_cards.transaction_number',
        //         'stock_cards.qty_begin',
        //         'stock_cards.qty_in',
        //         'stock_cards.qty_out',
        //         'stock_cards.qty_end',
        //         'stock_cards.created_at'
        //     );

        // // Filter tanggal
        // if ($dateFrom && $dateTo) {
        //     $query->whereBetween('stock_cards.created_at', [$dateFrom, $dateTo]);
        // }

        // // Filter nama item
        // if ($itemName) {
        //     $query->where('items.name', 'LIKE', "%{$itemName}%");
        // }

        // // Sorting berdasarkan request
        // $sortBy = $request->input('sort_by', 'item_name');
        // $sortDirection = $request->input('sort_direction', 'asc');
        // $query->orderBy($sortBy, $sortDirection);

        // // Grup dan ambil data
        // $stockMutations = $query->groupBy('stock_cards.item_id')->get();

        // Query menggunakan Eloquent
        $stockMutations = StockCard::select([
            'stock_cards.item_id',
            'items.name',
            DB::raw('(SELECT qty_begin
                    FROM stock_cards AS X
                    WHERE X.item_id = stock_cards.item_id
                        AND X.created_at = (
                            SELECT MIN(created_at)
                            FROM stock_cards AS Y
                            WHERE Y.item_id = stock_cards.item_id
                        )
                    ) AS qty_begin'),
            DB::raw('SUM(stock_cards.qty_in) AS qty_in'),
            DB::raw('SUM(stock_cards.qty_out) AS qty_out'),
            DB::raw('(SELECT qty_end
                    FROM stock_cards AS Z
                    WHERE Z.item_id = stock_cards.item_id
                        AND Z.created_at = (
                            SELECT MAX(created_at)
                            FROM stock_cards AS O
                            WHERE O.item_id = stock_cards.item_id
                        )
                    ) AS qty_end')
        ])
        ->join('items', 'stock_cards.item_id', '=', 'items.id')
        ->groupBy('stock_cards.item_id', 'items.name')
        ->get();


        return response()->json($stockMutations);
    }
}
