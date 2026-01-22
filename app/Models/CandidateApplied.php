<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class CandidateApplied extends Model
{
    use AsSource, Filterable;

    protected $table = 'candidate_applied';
    public $timestamps = false;

    protected $fillable = [
        'job_id',
        'job_title',
        'company_name',
        'full_name',
        'gender',
        'mobile_number',
        'email',
        'city_state',
        'experience_level',
        'applied_at',
        'status',
    ];

    protected $allowedSorts = [
        'id',
        'full_name',
        'job_title',
        'company_name',
        'applied_at',
        'status',
    ];

    protected $allowedFilters = [
        'full_name'   => \Orchid\Filters\Types\Like::class,
        'job_title'   => \Orchid\Filters\Types\Like::class,
        'company_name'=> \Orchid\Filters\Types\Like::class,
        'email'       => \Orchid\Filters\Types\Like::class,
    ];
}
