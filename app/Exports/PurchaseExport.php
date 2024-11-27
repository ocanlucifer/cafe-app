<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchaseExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $purchases;

    public function __construct($purchases)
    {
        $this->purchases = $purchases;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->purchases as $purchase) {
            foreach ($purchase->details as $detail) {
                $data[] = [
                    'Transaction Number' => $purchase->transaction_number,
                    'Vendor' => $purchase->vendor->name,
                    'Item Name' => $detail->item->name,
                    'Price' => $detail->price,
                    'Quantity' => $detail->quantity,
                    'Subtotal' => $detail->total_price,
                    'Total Price' => $purchase->total_amount,
                    'Date' => $purchase->created_at->format('d-m-Y'),
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Transaction Number', 'Vendor', 'Item Name', 'Price', 'Quantity', 'Subtotal', 'Total Price', 'Date',
        ];
    }
}
