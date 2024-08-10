<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'product_category_id',
        'image',
    ];
    public function category()
    {
        return $this->belongsTo(CategoryProduct::class, 'product_category_id');
    }

}
