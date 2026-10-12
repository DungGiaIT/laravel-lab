<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const DEFAULT_CURRENCY = 'VNĐ';

    public const DEFAULT_IMAGE = 'https://images.unsplash.com/photo-1509042239860-f550ce710b93';

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'display_image_url',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price).' '.$this->currency;
    }

    public function getFormattedTotalAmount($quantity = 1)
    {
        return number_format($this->price * $quantity).' '.$this->currency;
    }
}
