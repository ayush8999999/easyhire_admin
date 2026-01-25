<?php

namespace App\Orchid\Screens;

use App\Models\CandidateApplied;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Sight;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class CandidateAppliedViewScreen extends Screen
{
    public $name = 'Candidate Details';
    public $description = 'View complete candidate application (read-only)';

    public function query(CandidateApplied $candidate): array
    {
        return [
            'candidate' => $candidate,
        ];
    }

    public function commandBar(): array
    {
        return [];
    }

    public function layout(): array
    {
        return [

            Layout::legend('candidate', [
                Sight::make('full_name', 'Full Name'),
                Sight::make('gender', 'Gender'),
                Sight::make('date_of_birth', 'Date of Birth'),
                Sight::make('mobile_number', 'Mobile Number'),
                Sight::make('email', 'Email Address'),
                Sight::make('nationality', 'Nationality'),
            ])->title('1) Personal Information'),

            Layout::legend('candidate', [
                Sight::make('city_state', 'City & State'),
                Sight::make('willing_to_relocate', 'Willing to Relocate'),
                Sight::make('current_address', 'Current Address'),
            ])->title('2) Location Details'),

            Layout::legend('candidate', [
                Sight::make('highest_qualification', 'Highest Qualification'),
                Sight::make('specialization', 'Specialization'),
                Sight::make('year_of_passing', 'Year of Passing'),
                Sight::make('college_university', 'College / University'),
            ])->title('3) Educational Qualification'),

            Layout::legend('candidate', [
                Sight::make('experience_level', 'Experience Level'),
                Sight::make('current_job_title', 'Current / Last Job Title'),
                Sight::make('experience_duration', 'Employment Duration'),
                Sight::make('previous_company', 'Company Name'),
            ])->title('4) Work Experience'),

            Layout::legend('candidate', [
                Sight::make('key_skills_responsibilities', 'Key Skills & Responsibilities'),
            ])->title('5) Skills Summary'),

            Layout::legend('candidate', [
                Sight::make('linkedin_profile', 'LinkedIn Profile'),
                Sight::make('portfolio_link', 'Portfolio / Website / GitHub'),
            ])->title('6) Professional Links'),

            Layout::legend('candidate', [
                Sight::make('has_job_offer', 'Job Offer in Hand'),
                Sight::make('job_offer_details', 'Offer Details'),
                Sight::make('additional_information', 'Additional Information'),
            ])->title('7) Additional Information'),

            Layout::legend('candidate', [
                Sight::make('declaration_signed', 'Signed By'),
                Sight::make('declaration_date', 'Declaration Date'),
            ])->title('8) Declaration'),

            Layout::legend('candidate', [

            Sight::make('passport_file', 'Passport')
                ->render(function ($candidate) {
                    if (!$candidate->passport_file) {
                        return '<span class="text-muted">Not uploaded</span>';
                    }

                    return '<a href="' . url('/' . $candidate->passport_file) . '" 
                                target="_blank" 
                                class="btn btn-warning btn-sm">
                                View Passport
                            </a>';
                }),

            Sight::make('cv_file', 'Resume / CV')
                ->render(function ($candidate) {
                    if (!$candidate->cv_file) {
                        return '<span class="text-muted">Not uploaded</span>';
                    }

                    return '<a href="' . url('/' . $candidate->cv_file) . '" 
                                target="_blank" 
                                class="btn btn-primary btn-sm">
                                View CV
                            </a>';
                }),

        ])->title('9) Uploaded Documents'),

        ];
    }
    public function changeStatus(Request $request)
{
    $candidate = CandidateApplied::findOrFail($request->id);

    $candidate->status = $request->status;
    $candidate->save();

    Toast::success("Status updated to {$request->status}");

    return redirect()->route('platform.candidate.list');
}
}
