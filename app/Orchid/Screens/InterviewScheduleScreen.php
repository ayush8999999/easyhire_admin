<?php

namespace App\Orchid\Screens;

use App\Models\CandidateApplied;
use App\Models\InterviewSchedule;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Actions\Button;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class InterviewScheduleScreen extends Screen
{
    public $name = 'Schedule Interview';
    public $candidate;

    public function query(CandidateApplied $candidate): array
    {
        $this->candidate = $candidate;
        return ['candidate' => $candidate];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Save Schedule')
                ->method('save')
                ->parameters(['candidate' => $this->candidate->id]),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('interview_date')
                    ->type('date')
                    ->title('Interview Date')
                    ->required(),

                Input::make('interview_time')
                    ->type('time')
                    ->title('Interview Time')
                    ->required(),

                Select::make('interview_mode')
                    ->options([
                        'Online' => 'Online',
                        'In-Person' => 'In-Person',
                        'Phone' => 'Phone',
                    ])
                    ->title('Mode')
                    ->required(),

                Input::make('interviewer_name')
                    ->title('Interviewer'),

                TextArea::make('notes')
                    ->title('Notes'),
            ])
        ];
    }

    public function save(Request $request, CandidateApplied $candidate)
    {
        InterviewSchedule::create([
            'candidate_id' => $candidate->id,
            'interview_date' => $request->interview_date,
            'interview_time' => $request->interview_time,
            'interview_mode' => $request->interview_mode,
            'interviewer_name' => $request->interviewer_name,
            'notes' => $request->notes,
        ]);

        $candidate->status = 'interview_scheduled';
        $candidate->save();

        Toast::success('Interview Scheduled Successfully');

        return redirect()->route('platform.interviews.list');
    }
}
