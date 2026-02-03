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
                $mail->Subject = 'You are Shortlisted - ' . $candidate->job_title;
                $mail->Body = "
                <div style='background:#fff8e1;padding:20px;font-family:Arial'>
                <h2 style='color:#f57f17'>Congratulations {$candidate->full_name} 🎉</h2>
                <p>You have been shortlisted for <b>{$candidate->job_title}</b> at {$candidate->company_name}</p>
                <p><b>Interview Mode:</b> Online</p>
                <p><b>Link:</b> <a href='{$candidate->interview_link}'>Join Interview</a></p>
                <p style='margin-top:20px'>EasyHire Consultancy Team</p>
                </div>";
            }

            if ($type === 'rejected') {
                $mail->Subject = 'Application Update : EasyHire Consultancy';
                $mail->Body = "
                <div style='background:#fff8e1;padding:20px;font-family:Arial'>
                <h2 style='color:#f57f17'>Dear {$candidate->full_name}</h2>
                <p>Thank you for attending the interview for <b>{$candidate->job_title}</b> at {$candidate->company_name}</p>
                <p>Unfortunately you were not selected.</p>
                <p>We wish you success ahead.</p>
                </div>";
            }

            $mail->send();
            return true;

        } catch (Exception $e) {
            return false;
        }
    }
}
