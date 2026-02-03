<?php

namespace App\Orchid\Screens;

use App\Models\CandidateApplied;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class HiredCandidatesScreen extends Screen
{
    public $name = 'Hired Candidates';
    public $description = 'Company wise hired candidates';

    public function query(): array
    {
        return [
            'candidates' => CandidateApplied::where('status','hired')
                ->orderBy('company_name')
                ->paginate(10)
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('candidates', [

                TD::make('company_name','Company')->sort(),
                TD::make('full_name','Candidate'),
                TD::make('job_title','Role'),
                TD::make('email','Email'),
                TD::make('mobile_number','Mobile'),
                TD::make('experience_level','Experience'),
                TD::make('work_mode_preference','Work Mode'),
                TD::make('applied_at','Hired On')
                    ->render(fn($c)=>$c->applied_at->format('d M Y')),
            ])
        ];
    }
}
