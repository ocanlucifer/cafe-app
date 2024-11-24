<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->sales as $sale) {
            foreach ($sale->details as $detail) {
                $data[] = [
                    'Transaction Number' => $sale->transaction_number,
                    'Customer' => $sale->customer->name,
                    'Item Name' => $detail->menuItem->name,
                    'Quantity' => $detail->quantity,
                    'Price' => $detail->price,
                    'Discount' => $detail->discount,
                    'Subtotal' => $detail->subtotal,
                    'Total Price' => $sale->total_price,
                    'Total Discount' => $sale->discount,
                    'Total After Discount' => $sale->total_price - $sale->discount,
                    'Date' => $sale->created_at->format('d-m-Y'),
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Transaction Number', 'Customer', 'Item Name', 'Quantity', 'Price', 'Discount', 'Subtotal', 'Total Price', 'Total Discount', 'Total After Discount', 'Date',
        ];
    }
}
