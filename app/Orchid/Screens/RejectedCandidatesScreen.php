<?php

namespace App\Orchid\Screens;

use App\Models\CandidateApplied;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class RejectedCandidatesScreen extends Screen
{
    public $name = 'Rejected Candidates';

    public function query(): array
    {
        return [
            'candidates' => CandidateApplied::where('status', 'rejected')
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
