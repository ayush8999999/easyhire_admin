<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CandidateApplied; // ✅ ADD THIS

class InterviewSchedule extends Model
{
    protected $fillable = [
        'candidate_id',
        'interview_date',
        'interview_time',
        'interview_mode',
        'interviewer_name',
        'notes',
    ];

    public function candidate()
    {
        return $this->belongsTo(CandidateApplied::class, 'candidate_id');
    }
}
