<?php

namespace App\Orchid\Screens;

use App\Models\CandidateApplied;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;

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

                TD::make('Actions')
                    ->render(fn($c) =>
                        Link::make('Schedule Interview')
                            ->route('platform.interview.schedule', $c->id)
                            ->class('btn btn-sm btn-primary')
                    ),
            ]),
        ];
    }
}
