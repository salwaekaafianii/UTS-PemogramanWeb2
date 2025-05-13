<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name', 'slug', 'sku', 'description', 'price', 'stock', 'product_category_id', 'image_url' , 'is_active' // menyesuaikan dengan kolom yang ada
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'product_category_id');
    }

}

