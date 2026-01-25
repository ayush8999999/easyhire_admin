<?php

namespace App\Orchid\Screens;

use App\Models\CandidateApplied;          // ✅ IMPORT MODEL
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;        // ✅ IMPORT LAYOUT
use Orchid\Screen\TD; 

class ShortlistedCandidatesScreen extends Screen
{
    public $name = 'Shortlisted Candidates';

    public function query(): array
    {
        return [
            'candidates' => CandidateApplied::where('status', 'shortlisted')
                ->latest('applied_at')
                ->paginate(10),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('candidates', [
                TD::make('full_name', 'Name'),
                TD::make('job_title', 'Job'),
                TD::make('company_name', 'Company'),
                TD::make('mobile_number', 'Mobile'),
                TD::make('email', 'Email'),
                TD::make('applied_at', 'Applied On')
                    ->render(fn($c) => date('d M Y', strtotime($c->applied_at))),
            ]),
        ];
    }
}

