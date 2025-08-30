<?php

namespace App\Jobs;

use App\Models\Student;
use App\Models\ParentCredential;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SendAttendanceEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 🔑 Declare the properties here
    protected $studentId;
    protected $parentId;
    protected $status;
    protected $dateTime;

    public function __construct($studentId, $parentId, $status, $dateTime)
    {
        $this->studentId = $studentId;
        $this->parentId  = $parentId;
        $this->status    = $status;
        $this->dateTime  = $dateTime;
    }

    public function handle(): void
    {
        // Fetch models fresh
        $student = Student::find($this->studentId);
        $parent  = ParentCredential::find($this->parentId);

        if (!$student || !$parent || !$parent->parent_email) {
            Log::warning("Skipping email: Missing student/parent data (student_id={$this->studentId}, parent_id={$this->parentId})");
            return;
        }

        $dateTime      = Carbon::parse($this->dateTime);
        $studentName   = "{$student->students_first_name} {$student->students_last_name}";
        $parentLast    = $parent->parent_last_name;
        $formattedTime = $dateTime->format('h:i A');
        $formattedDate = $dateTime->format('F j, Y');

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'usepasstmc.system@gmail.com';
            $mail->Password   = 'rhkwujluyfwnaxpy';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('usepasstmc.system@gmail.com', 'USePass System');
            $mail->addAddress($parent->parent_email);

            $mail->isHTML(true);
            $mail->Subject = 'Student Attendance Report';

            $logoPath = public_path('images/usep-logo-small.png');
            if (file_exists($logoPath)) {
                $mail->addEmbeddedImage($logoPath, 'useplogo');
            }

            $mail->Body = "
                <div style='font-family: Arial, sans-serif;'>
                    <div style='text-align: center; margin-bottom: 10px;'>
                        <img src='cid:useplogo' alt='USePASS Logo' style='width: 120px;'>
                        <h2 style='color: #2d3748;'>USePASS</h2>
                    </div>
                    <p><strong>Date:</strong> {$formattedDate}</p>
                    <p style='margin-top: 20px;'>Dear Mr./Ms. {$parentLast},</p>
                    <p style='text-align: justify;'>
                        " . (
                $this->status === 'Time In'
                    ? "We would like to inform you that your child, <strong>{$studentName}</strong>, has entered the USeP Tagum Unit at <strong>{$formattedTime}</strong>."
                    : "We would like to inform you that your child, <strong>{$studentName}</strong>, has left the USeP Tagum Unit at <strong>{$formattedTime}</strong>."
                ) . "
                        <br><br>Thank you for trusting USePass.
                    </p>
                </div>
            ";

            $mail->send();
        } catch (Exception $e) {
            Log::error("Email failed: " . $e->getMessage());
        }
    }
}
