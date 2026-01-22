<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $jobTitle;

    public function __construct($name, $jobTitle)
    {
        $this->name = $name;
        $this->jobTitle = $jobTitle;
    }

    public function build()
    {
        return $this
            ->subject('Application Received - ' . $this->jobTitle)
            ->view('emails.application-confirmation')
            ->with([
                'name' => $this->name,
                'job_title' => $this->jobTitle,
            ]);
    }
}