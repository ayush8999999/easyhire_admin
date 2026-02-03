<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CandidateMailer
{
    public static function send($type, $candidate)
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP config
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'xbnrxoohgqqrmlrskt@gmail.com';
            $mail->Password   = 'wjyknpvgigbkzpfw';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('growwithuseasyhire@gmail.com', 'EasyHire');
            $mail->addAddress($candidate->email, $candidate->full_name);

            $mail->isHTML(true);

            if ($type === 'shortlisted') {

                $interview = $candidate->interview;

                $date = $interview?->interview_date
                    ? date('d M Y', strtotime($interview->interview_date))
                    : 'To be informed';

                $time = $interview?->interview_time
                    ? date('h:i A', strtotime($interview->interview_time))
                    : 'To be informed';

                $mode = $interview?->interview_mode ?? 'To be informed';
                $interviewer = $interview?->interviewer_name ?? 'HR Team';

                $link = $candidate->interview_link ?? '#'; // optional

                $mail->Subject = 'You Are Shortlisted : Interview Scheduled';

                $mail->Body = "
                <div style='max-width:650px;margin:auto;background:#fffdf5;border-radius:10px;
                            box-shadow:0 0 20px rgba(0,0,0,0.08);font-family:Arial'>

                    <div style='background:#fbc02d;padding:20px;text-align:center'>
                        <h1 style='margin:0'>EasyHire Consultancy</h1>
                        <p style='margin:5px 0'>Interview Selection</p>
                    </div>

                    <div style='padding:25px'>
                        <h2 style='color:#f57f17'>Dear {$candidate->full_name},</h2>

                        <p><b>Congratulations! 🎉</b></p>

                        <p>
                        You have been shortlisted for <b>{$candidate->job_title}</b>
                        at <b>{$candidate->company_name}</b>.
                        </p>

                        <div style='background:#fff8e1;padding:15px;border-left:5px solid #fbc02d'>
                            <h3>📅 Interview Details</h3>
                            <p><b>Company:</b> {$candidate->company_name}</p>
                            <p><b>Position:</b> {$candidate->job_title}</p>
                            <p><b>Date:</b> {$date}</p>
                            <p><b>Time:</b> {$time}</p>
                            <p><b>Mode:</b> {$mode}</p>
                            <p><b>Interviewer:</b> {$interviewer}</p>
                        </div>

                        <div style='background:#fff3cd;padding:15px;margin-top:15px'>
                            <h4>📌 Instructions</h4>
                            <ul>
                                <li>Join 10 minutes early</li>
                                <li>Keep your ID and CV ready</li>
                                <li>Dress professionally</li>
                                <li>Sit in a quiet place</li>
                            </ul>
                        </div>

                        <p>Good Luck!<br><b>EasyHire Consultancy Team</b></p>
                    </div>

                    <div style='background:#fbc02d;text-align:center;padding:10px;font-size:12px'>
                        © " . date('Y') . " EasyHire Consultancy. All rights reserved.
                    </div>
                </div>";
            }

            if ($type === 'rejected') {

            $mail->Subject = 'Application Update : EasyHire Consultancy';

            $mail->Body = "
            <div style='max-width:650px;margin:auto;background:#fffdf5;border-radius:10px;
                        box-shadow:0 0 20px rgba(0,0,0,0.08);font-family:Arial'>

                <div style='background:#fbc02d;padding:20px;text-align:center'>
                    <h1 style='margin:0'>EasyHire Consultancy</h1>
                    <p style='margin:5px 0'>Application Update</p>
                </div>

                <div style='padding:25px;color:#333'>
                    <h2 style='color:#f57f17'>Dear {$candidate->full_name},</h2>

                    <p>
                        Thank you for attending the interview for 
                        <b>{$candidate->job_title}</b> at 
                        <b>{$candidate->company_name}</b>.
                    </p>

                    <p>
                        After careful review, we regret to inform you that you have not been 
                        selected for this position at this time.
                    </p>

                    <div style='background:#fff3cd;padding:15px;border-left:5px solid #fbc02d;margin:20px 0'>
                        <b>📌 Note:</b><br>
                        Due to high competition and limited vacancies, we were unable to move 
                        forward with your profile for this role.
                    </div>

                    <p>
                        Please do not feel disappointed. Many new opportunities are regularly 
                        posted on our platform, and we encourage you to continue applying.
                    </p>

                    <p>
                        We truly appreciate your interest and wish you success in your future 
                        career journey.
                    </p>

                    <p style='font-weight:bold'>
                        Kind Regards,<br>
                        EasyHire Consultancy Team
                    </p>
                </div>

                <div style='background:#fbc02d;text-align:center;padding:10px;font-size:12px'>
                    © " . date('Y') . " EasyHire Consultancy. All rights reserved.
                </div>

            </div>
            ";
        }

        if ($type === 'interview_scheduled') {

        $interview = $candidate->interview;

        $date = date('d M Y', strtotime($interview->interview_date));
        $time = date('h:i A', strtotime($interview->interview_time));
        $mode = $interview->interview_mode;
        $interviewer = $interview->interviewer_name;

        $mail->Subject = 'Interview Scheduled : EasyHire Consultancy';

        $mail->Body = "
        <div style='max-width:650px;margin:auto;background:#fffdf5;border-radius:10px;
                    box-shadow:0 0 20px rgba(0,0,0,0.08);font-family:Arial'>

            <div style='background:#fbc02d;padding:20px;text-align:center'>
                <h1 style='margin:0'>EasyHire Consultancy</h1>
                <p style='margin:5px 0'>Interview Scheduled</p>
            </div>

            <div style='padding:25px'>
                <h2 style='color:#f57f17'>Dear {$candidate->full_name},</h2>

                <p>Your interview has been scheduled successfully.</p>

                <div style='background:#fff8e1;padding:15px;border-left:5px solid #fbc02d'>
                    <h3>📅 Interview Details</h3>
                    <p><b>Company:</b> {$candidate->company_name}</p>
                    <p><b>Position:</b> {$candidate->job_title}</p>
                    <p><b>Date:</b> {$date}</p>
                    <p><b>Time:</b> {$time}</p>
                    <p><b>Mode:</b> {$mode}</p>
                    <p><b>Interviewer:</b> {$interviewer}</p>
                </div>

                <p>We look forward to meeting you.</p>

                <p>Best Regards,<br><b>EasyHire Consultancy Team</b></p>
            </div>

            <div style='background:#fbc02d;text-align:center;padding:10px;font-size:12px'>
                © " . date('Y') . " EasyHire Consultancy. All rights reserved.
            </div>

        </div>";
    }

        if ($type === 'hired') {

        $mail->Subject = 'Congratulations! You Are Selected : ' . $candidate->company_name;

        $mail->Body = "
        <div style='max-width:650px;margin:auto;background:#fffdf5;border-radius:10px;
                    box-shadow:0 0 20px rgba(0,0,0,0.08);font-family:Arial'>

            <div style='background:#fbc02d;padding:20px;text-align:center'>
                <h1 style='margin:0'>EasyHire Consultancy</h1>
                <p style='margin:5px 0'>Final Selection</p>
            </div>

            <div style='padding:25px;color:#333'>
                <h2 style='color:#f57f17'>Dear {$candidate->full_name},</h2>

                <p>
                    We are happy to inform you that you have been successfully selected for 
                    the position of <b>{$candidate->job_title}</b> at 
                    <b>{$candidate->company_name}</b> 🎊
                </p>

                <p>Your performance during the interview was appreciated by the employer.</p>

                <div style='background:#fff8e1;padding:15px;border-left:5px solid #fbc02d;margin:20px 0'>
                    <h4 style='margin-top:0;color:#f57f17'>📌 Next Steps Include</h4>
                    <ul>
                        <li>Offer letter confirmation</li>
                        <li>Document verification</li>
                        <li>Travel and joining guidance</li>
                        <li>Company onboarding process</li>
                    </ul>
                </div>

                <p>
                    Our team will contact you soon with full joining instructions.
                </p>

                <p>
                    Welcome to your new career opportunity!
                </p>

                <p style='font-weight:bold'>
                    Congratulations Once Again,<br>
                    EasyHire Consultancy Recruitment Team
                </p>
            </div>

            <div style='background:#fbc02d;text-align:center;padding:10px;font-size:12px'>
                © " . date('Y') . " EasyHire Consultancy. All rights reserved.
            </div>

        </div>";
    }

            $mail->send();
            return true;

        } catch (Exception $e) {
            return false;
        }
    }
}
