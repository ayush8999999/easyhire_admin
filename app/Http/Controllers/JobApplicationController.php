<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationConfirmation;

class JobApplicationController extends Controller
{
    public function store(Request $request)
    {
        // Basic validation (add more rules as needed)
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'job_title' => 'required|string|max:255',
            // add other fields you want to validate...
        ]);

        // Optional: Save to database here later
        // JobApplication::create($validated);

        // Send confirmation email
        try {
            Mail::to($request->email)->send(new ApplicationConfirmation(
                $request->full_name,
                $request->job_title
            ));

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not send confirmation email. Please try again.'
            ], 500);
        }
    }
}