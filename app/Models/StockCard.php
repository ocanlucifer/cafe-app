<?php

// app/Models/StockCard.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCard extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'quantity_in', 'quantity_out'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

