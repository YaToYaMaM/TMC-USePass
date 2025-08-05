<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\ParentCredential;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentAttendanceReport;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class StudentRecordController extends Controller
{
    public function fetchRecords(Request $request)
    {
        $query = DB::table('students_records')
            ->join('students', 'students.students_id', '=', 'students_records.students_id')
            ->select(
                'students.students_id as student_id',
                DB::raw("CONCAT(students.students_first_name, ' ', students.students_middle_initial, '. ', students.students_last_name) as name"),
                'students.students_program',
                'students.students_major',
                'students_unit',
                DB::raw('DATE(students_records.created_at) as date'),
                'students_records.record_in',
                'students_records.record_out'
            );

        if ($request->filled('date')) {
            $query->whereDate('students_records.created_at', $request->date);
        }

        if ($request->filled('program')) {
            $query->where('students.students_program', $request->program);
        }

        $records = $query->get();

        return response()->json($records);
    }

    /**
     * Search for students currently inside the campus
     * (students who have checked in but not checked out today)
     */
    public function searchActiveStudents(Request $request)
    {
        $query = $request->get('query');

        if (empty($query) || strlen(trim($query)) < 2) {
            return response()->json([
                'students' => [],
                'message' => 'Query must be at least 2 characters long.'
            ]);
        }

        try {
            $students = DB::table('students_records')
                ->join('students', 'students.students_id', '=', 'students_records.students_id')
                ->select(
                    'students.students_id as id',
                    'students.students_id as id_number',
                    DB::raw("CONCAT(students.students_first_name, ' ', students.students_middle_initial, '. ', students.students_last_name) as full_name"),
                    'students.students_program as program',
                    'students.students_major as major',
                    'students.students_unit as unit',
                    'students.students_profile_image as profile',
                    'students_records.record_in',
                    'students_records.record_out'
                )
                // Only get records from today
                ->whereDate('students_records.created_at', today())
                // Only students who have checked in but not out (record_out is null)
                ->whereNotNull('students_records.record_in')
                ->whereNull('students_records.record_out')
                // Search by name, ID, or program
                ->where(function($q) use ($query) {
                    $q->where(DB::raw("CONCAT(students.students_first_name, ' ', students.students_middle_initial, '. ', students.students_last_name)"), 'LIKE', "%{$query}%")
                        ->orWhere('students.students_id', 'LIKE', "%{$query}%")
                        ->orWhere('students.students_program', 'LIKE', "%{$query}%")
                        ->orWhere('students.students_major', 'LIKE', "%{$query}%");
                })
                ->orderBy('students.students_first_name')
                ->limit(10) // Limit to 10 results for performance
                ->get();

            return response()->json([
                'students' => $students,
                'count' => $students->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Student search error: ' . $e->getMessage());

            return response()->json([
                'students' => [],
                'message' => 'An error occurred while searching students.',
                'error' => $e->getMessage() // Remove this in production
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $date = $request->query('date');

        if (empty($date)) {
            return response()->json([
                'message' => 'Date parameter is required.'
            ], 400);
        }

        $records = StudentRecord::whereDate('record_in', $date)->get();

        return response()->json($records);
    }
    public function log(Request $request)
    {
        $request->validate([
            'students_id' => 'required|exists:students,students_id',
        ]);

        $studentsId = $request->input('students_id');
        $now = Carbon::now('Asia/Manila');

        $student = Student::where('students_id', $studentsId)->first();
        $parent = ParentCredential::where('students_id', $studentsId)->first();

        if (!$student || !$parent || !$parent->parent_email) {
            return response()->json(['error' => 'Missing student or parent data.'], 422);
        }

        // Get today's latest record
        $latestRecord = StudentRecord::where('students_id', $studentsId)
            ->whereDate('record_in', $now->toDateString())
            ->latest()
            ->first();

        $status = '';

        if (!$latestRecord || $latestRecord->record_out !== null) {
            // Time In
            StudentRecord::create([
                'students_id' => $studentsId,
                'record_in' => $now,
                'record_out' => null,
            ]);
            $status = 'Time In';
        } else {
            // Time Out
            $latestRecord->update([
                'record_out' => $now,
            ]);
            $status = 'Time Out';
        }

        // Send email using PHPMailer
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'usepasstmc.system@gmail.com'; // your Gmail
            $mail->Password = 'rhkwujluyfwnaxpy'; // your app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('usepasstmc.system@gmail.com', 'USePass System');
            $mail->addAddress($parent->parent_email); // parent email

            $mail->isHTML(true);
            $mail->Subject = 'Student Attendance Report';

            $studentName = "{$student->students_first_name} {$student->students_last_name}";
            $formattedTime = $now->format('h:i A');
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
                <p><strong>Date:</strong> {$now->format('F j, Y')}</p>
                <p style='margin-top: 20px;'>Dear Parent,</p>
                <p>Your child <strong>{$studentName}</strong> has recorded a <strong>{$status}</strong> at <strong>{$formattedTime}</strong>.</p>
                <p>Thank you for using USePass.</p>
            </div>
        ";

            $mail->send();
        } catch (Exception $e) {
            \Log::error('Email failed to send: ' . $e->getMessage());
        }

        return response()->json([
            'status' => strtolower($status),
            'time' => $now->toDateTimeString(),
        ]);
    }

}
