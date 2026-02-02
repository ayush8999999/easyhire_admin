<?php

namespace App\Orchid\Screens;

use App\Models\InterviewSchedule;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class ScheduledInterviewsScreen extends Screen
{
    public $name = 'Scheduled Interviews';

    public function query(): array
    {
        return [
            'interviews' => InterviewSchedule::with('candidate')->paginate(10),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('interviews', [

                TD::make('candidate', 'Candidate')
                    ->render(fn ($i) => $i->candidate ? $i->candidate->full_name : '-'),

                TD::make('job', 'Job')
                    ->render(fn ($i) => $i->candidate ? $i->candidate->job_title : '-'),

                TD::make('date', 'Interview Date')
                    ->render(fn ($i) => $i->interview_date),

                TD::make('time', 'Interview Time')
                    ->render(fn ($i) => $i->interview_time),

                TD::make('mode', 'Mode')
                    ->render(fn ($i) => $i->interview_mode),

                TD::make('interviewer', 'Interviewer')
                    ->render(fn ($i) => $i->interviewer_name),

            ]),
        ];
    }
}
