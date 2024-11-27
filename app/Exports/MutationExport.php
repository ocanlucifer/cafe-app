<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MutationExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $stockMutations;

    public function __construct($stockMutations)
    {
        $this->stockMutations = $stockMutations;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->stockMutations as $mutation) {
                $data[] = [
                    'Item Name' => $mutation->name,
                    'Begin Qty' => $mutation->qty_begin,
                    'In Qty' => $mutation->qty_in,
                    'Out Qty' => $mutation->qty_out,
                    'End Qty' => $mutation->qty_end,
                ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Item Name', 'Begin Qty', 'In Qty', 'Out Qty', 'End Qty',
        ];
    }
}
