<?php

namespace App\Models;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name','detail', 'stock', 'price', 'discount'
    ];
    public function reviews()
    {
        return $this->hasMany(Review::class);

    }
    public function category()
    {
        return $this->belongsTo(Category::class);

    }
}
