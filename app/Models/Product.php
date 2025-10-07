<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Các field có thể gán hàng loạt
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
    ];
}
