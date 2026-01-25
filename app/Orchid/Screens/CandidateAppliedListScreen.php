<?php

namespace App\Orchid\Screens;

use App\Models\CandidateApplied;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class CandidateAppliedListScreen extends Screen
{
    public $name = 'Candidates Applied';
    public $description = 'View job applications (read-only)';

    public function query(Request $request): array
    {
        $query = CandidateApplied::query();

        // TEXT FILTERS
        if ($request->filled('name')) {
            $query->where('full_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('job_title')) {
            $query->where('job_title', 'like', '%' . $request->job_title . '%');
        }

        if ($request->filled('company')) {
            $query->where('company_name', 'like', '%' . $request->company . '%');
        }

        // STATUS FILTER
        // if ($request->filled('status')) {
        //     $query->where('status', $request->status);
        // }

        // DATE RANGE FILTER
        if ($request->filled('date_from')) {
            $query->whereDate('applied_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('applied_at', '<=', $request->date_to);
        }

        return [
            'candidates' => $query->latest('applied_at')->paginate(10),
            'filters' => $request->only([
                'name',
                'job_title',
                'company',
                // 'status',
                'date_from',
                'date_to',
            ]),
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Export All Candidates')
            ->icon('bs.file-earmark-excel')
            ->method('exportAll')
            ->rawClick()   // ⭐ REQUIRED
            ->class('btn btn-success'),
        ];
    }

   public function exportCandidate(int $id)
    {
        $candidate = CandidateApplied::findOrFail($id);

        $filename = "candidate_{$id}.csv";

        $headers = [
            "Content-Type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
        ];

        $columns = [
            'ID',
            'Job Title',
            'Company',
            'Full Name',
            'Gender',
            'Date of Birth',
            'Mobile',
            'Email',
            'City / State',
            'Nationality',
            'Experience Level',
            'Current Job Title',
            'Previous Company',
            'Experience Duration',
            'Preferred Job Roles',
            'Preferred Job Locations',
            'Work Mode',
            'Current Salary',
            'Expected Salary',
            'Notice Period',
            'Applied At',
        ];

        $callback = function () use ($candidate, $columns) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            fputcsv($file, [
                $candidate->id,
                $candidate->job_title,
                $candidate->company_name,
                $candidate->full_name,
                $candidate->gender,
                $candidate->date_of_birth,
                $candidate->mobile_number,
                $candidate->email,
                $candidate->city_state,
                $candidate->nationality,
                $candidate->experience_level,
                $candidate->current_job_title,
                $candidate->previous_company,
                $candidate->experience_duration,
                $candidate->preferred_job_roles,
                $candidate->preferred_job_locations,
                $candidate->work_mode_preference,
                $candidate->current_salary,
                $candidate->expected_salary,
                $candidate->notice_period,
                $candidate->applied_at,
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportAll(Request $request)
{
    $query = CandidateApplied::query();

    // SAME FILTERS AS query()
    if ($request->filled('name')) {
        $query->where('full_name', 'like', '%' . $request->name . '%');
    }

    if ($request->filled('job_title')) {
        $query->where('job_title', 'like', '%' . $request->job_title . '%');
    }

    if ($request->filled('company')) {
        $query->where('company_name', 'like', '%' . $request->company . '%');
    }

    if ($request->filled('date_from')) {
        $query->whereDate('applied_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('applied_at', '<=', $request->date_to);
    }

    $candidates = $query->latest('applied_at')->get();

    $filename = 'all_candidates_' . now()->format('Ymd_His') . '.csv';

    $headers = [
        "Content-Type"        => "text/csv",
        "Content-Disposition" => "attachment; filename={$filename}",
    ];

    $columns = [
        'ID',
        'Job Title',
        'Company',
        'Full Name',
        'Gender',
        'DOB',
        'Mobile',
        'Email',
        'City / State',
        'Nationality',
        'Experience Level',
        'Current Job Title',
        'Previous Company',
        'Experience Duration',
        'Preferred Roles',
        'Preferred Locations',
        'Work Mode',
        'Current Salary',
        'Expected Salary',
        'Notice Period',
        'Applied At',
    ];

    $callback = function () use ($candidates, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($candidates as $c) {
            fputcsv($file, [
                $c->id,
                $c->job_title,
                $c->company_name,
                $c->full_name,
                $c->gender,
                $c->date_of_birth,
                $c->mobile_number,
                $c->email,
                $c->city_state,
                $c->nationality,
                $c->experience_level,
                $c->current_job_title,
                $c->previous_company,
                $c->experience_duration,
                $c->preferred_job_roles,
                $c->preferred_job_locations,
                $c->work_mode_preference,
                $c->current_salary,
                $c->expected_salary,
                $c->notice_period,
                $c->applied_at,
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}


    public function layout(): array
    {
        return [

            /* ============================
             * ADVANCED FILTERS
             * ============================ */
            Layout::rows([

                Input::make('name')
                    ->title('Candidate Name')
                    ->placeholder('Search candidate')
                    ->datalist(
                        CandidateApplied::pluck('full_name')->unique()->take(50)->toArray()
                    )
                    ->horizontal(),

                Input::make('job_title')
                    ->title('Job Title')
                    ->placeholder('Search job title')
                    ->datalist(
                        CandidateApplied::pluck('job_title')->unique()->take(50)->toArray()
                    )
                    ->horizontal(),

                Input::make('company')
                    ->title('Company')
                    ->placeholder('Search company')
                    ->datalist(
                        CandidateApplied::pluck('company_name')->unique()->take(50)->toArray()
                    )
                    ->horizontal(),

                // Select::make('status')
                //     ->title('Application Status')
                //     ->options([
                //         ''            => 'All',
                //         'new'         => 'New',
                //         'reviewed'    => 'Reviewed',
                //         'shortlisted' => 'Shortlisted',
                //         'rejected'    => 'Rejected',
                //         'hired'       => 'Hired',
                //     ])
                //     ->empty('All Status'),

                Group::make([
                    Input::make('date_from')
                        ->title('Applied From')
                        ->type('date'),

                    Input::make('date_to')
                        ->title('Applied To')
                        ->type('date'),
                ]),

                Button::make('Apply Filters')
                    ->icon('bs.filter')
                    ->method('applyFilters')
                    ->class('btn btn-primary'),

            ])->title('Advanced Filters'),

            /* ============================
             * TABLE
             * ============================ */
            Layout::table('candidates', [

                TD::make('id', 'ID')->sort(),

                TD::make('full_name', 'Candidate')
                    ->render(fn($c) =>
                        "<strong>{$c->full_name}</strong><br>
                         <small class='text-muted'>{$c->email}</small>"
                    ),

                TD::make('job_title', 'Job')
                    ->render(fn($c) =>
                        "<strong>{$c->job_title}</strong><br>
                         <small class='text-muted'>{$c->company_name}</small>"
                    ),

                TD::make('experience_level', 'Experience')
                    ->alignCenter(),

                TD::make('status', 'Status')
                    ->alignCenter()
                    ->render(fn($c) => match ($c->status) {
                        'new'         => "<span class='badge bg-primary'>New</span>",
                        'reviewed'    => "<span class='badge bg-info'>Reviewed</span>",
                        'shortlisted' => "<span class='badge bg-success'>Shortlisted</span>",
                        'rejected'    => "<span class='badge bg-danger'>Rejected</span>",
                        'hired'       => "<span class='badge bg-dark'>Hired</span>",
                        default       => "<span class='badge bg-secondary'>Unknown</span>",
                    }),

                TD::make('applied_at', 'Applied On')
                    ->render(fn($c) => date('d M Y', strtotime($c->applied_at)))
                    ->sort(),

                  TD::make('Actions')
    ->alignCenter()
    ->render(fn ($c) => 
        '<div class="d-flex flex-wrap gap-1 justify-content-center">' .

            Link::make('View')
                ->route('platform.candidate.view', $c->id)
                ->class('btn btn-sm btn-outline-primary')
                ->render() .

            Button::make('Excel')
                ->icon('bs.file-earmark-excel')
                ->method('exportCandidate')
                ->parameters(['id' => $c->id])
                ->rawClick()
                ->class('btn btn-sm btn-outline-success')
                ->render() .

            Button::make('Shortlist')
                ->method('changeStatus')
                ->parameters(['id' => $c->id, 'status' => 'shortlisted'])
                ->class('btn btn-sm btn-success')
                ->rawClick()
                ->render() .

            Button::make('Reject')
                ->method('changeStatus')
                ->parameters(['id' => $c->id, 'status' => 'rejected'])
                ->class('btn btn-sm btn-danger')
                ->rawClick()
                ->render()

        . '</div>'
    ),


            ]),
        ];
    }

    public function applyFilters(Request $request)
    {
        return redirect()->route('platform.candidate.list', $request->all());
    }
    public function changeStatus(Request $request)
{
    $candidate = CandidateApplied::findOrFail($request->id);

    $candidate->status = $request->status;
    $candidate->save();

    \Orchid\Support\Facades\Toast::success("Status updated to {$request->status}");

    return redirect()->back();
}

}
