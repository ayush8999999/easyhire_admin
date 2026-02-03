<?php

namespace App\Orchid\Screens;

use App\Models\InterviewSchedule;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

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

                    TD::make('Actions')
                    ->alignCenter()
                    ->render(fn ($i) => 
                        '<div class="d-flex gap-1">' .

                        Button::make('Hire')
                            ->method('changeStatusFromInterview')
                            ->parameters([
                                'candidate_id' => $i->candidate_id,
                                'status' => 'hired'
                            ])
                            ->class('btn btn-sm btn-dark')
                            ->rawClick()
                            ->render() .

                        Button::make('Reject')
                            ->method('changeStatusFromInterview')
                            ->parameters([
                                'candidate_id' => $i->candidate_id,
                                'status' => 'rejected'
                            ])
                            ->class('btn btn-sm btn-danger')
                            ->rawClick()
                            ->render()

                        . '</div>'
                    ),
            ]),
        ];
    }

    public function changeStatusFromInterview(Request $request)
    {
        $candidate = \App\Models\CandidateApplied::findOrFail($request->candidate_id);

        $candidate->status = $request->status;
        $candidate->save();

        Toast::success("Candidate marked as {$request->status}");

        return redirect()->back();
    }
}
