<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Product extends Model
{
    use AsSource, Filterable;

    protected $table = 'products';
    public $timestamps = true;

    protected $fillable = [
        'name', 'category', 'price', 'stock',
    ];

    protected $allowedSorts = ['id', 'name', 'category', 'price', 'stock'];

    // Option A: Remove filters entirely
    // protected $allowedFilters = [];

    // Option B: Use built-in filters
    protected $allowedFilters = [
        'name'     => \Orchid\Filters\Types\Like::class,
        'category' => \Orchid\Filters\Types\Like::class,
    ];
}