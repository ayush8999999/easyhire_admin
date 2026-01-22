<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Course extends Model
{
    use AsSource, Filterable;

    protected $table = 'courses';
    public $timestamps = true;

    protected $fillable = [
        'name', 'full_name', 'description', 'fees', 'duration_months',
    ];

    protected $allowedSorts = [
        'id', 'name', 'full_name', 'fees', 'duration_months'
    ];

    protected $allowedFilters = [
        'name'     => \Orchid\Filters\Types\Like::class,
        'full_name'=> \Orchid\Filters\Types\Like::class,
    ];
}