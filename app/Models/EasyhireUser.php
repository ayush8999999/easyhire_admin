<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class EasyhireUser extends Model
{
    use AsSource, Filterable;

    protected $table = 'easyhire_users';

    protected $allowedFilters = [
        'full_name',
        'email',
        'mobile_number',
        'status',
    ];

    protected $allowedSorts = [
        'id',
        'created_at',
    ];
}
