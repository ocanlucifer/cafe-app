<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'category_id',
        'type_id',
        'active',
        'price',
    ];

    /**
     * Relationships.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    // Relasi ke SalesDetail
    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class); // Menghubungkan dengan SalesDetail
    }
}
