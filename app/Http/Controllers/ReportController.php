<?php

// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth();

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
                    ->with(['customer', 'item'])
                    ->get();

        return view('reports.sales', compact('sales'));
    }

    public function inventoryReport()
    {
        $inventories = Inventory::with('item')->get();
        return view('reports.inventory', compact('inventories'));
    }
}
