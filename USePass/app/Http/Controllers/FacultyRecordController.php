<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FacultyRecords;
use App\Models\FacultyStaff;
use Carbon\Carbon;

class FacultyRecordController extends Controller
{
    public function fetchFacultyRecords(Request $request)
    {
        $query = DB::table('facultystaff_records')
        ->join('facultystaff', 'facultystaff.faculty_id', '=', 'facultystaff_records.faculty_id')
            ->select(
                'facultystaff.faculty_id as faculty_id',
                DB::raw("CONCAT(facultystaff.faculty_first_name, ' ', facultystaff.faculty_middle_initial, '. ', facultystaff.faculty_last_name) as name"),
                'facultystaff.faculty_department',
                'facultystaff.faculty_unit',
                DB::raw('DATE(facultystaff_records.created_at) as date'),
                'facultystaff_records.record_in',
                'facultystaff_records.record_out'
            );

        if ($request->filled('date')) {
            $query->whereDate('facultystaff_records.created_at', $request->date);
        }

        if ($request->filled('department')) {
            $query->where('facultystaff.faculty_department', $request->department); // ✅ fixed typo
        }

        $records = $query->get();

        return response()->json($records);
    }

    public function searchActiveFaculty(Request $request)
    {
        $query = $request->get('query');

        if (empty($query) || strlen(trim($query)) < 2) {
            return response()->json([
                'faculty' => [],
                'message' => 'Query must be at least 2 characters long.'
            ]);
        }

        try {
            $faculty = DB::table('facultystaff_records')
                ->join('facultystaff', 'facultystaff.faculty_id', '=', 'facultystaff_records.faculty_id')
                ->select(
                    'facultystaff.faculty_id as id',
                    'facultystaff.faculty_id as employee_id',
                    DB::raw("CONCAT(facultystaff.faculty_first_name, ' ',
                CASE
                    WHEN facultystaff.faculty_middle_initial IS NOT NULL AND facultystaff.faculty_middle_initial != ''
                    THEN CONCAT(facultystaff.faculty_middle_initial, '. ')
                    ELSE ''
                END,
                facultystaff.faculty_last_name) as name"),
                    'facultystaff.faculty_first_name as first_name',
                    'facultystaff.faculty_last_name as last_name',
                    'facultystaff.faculty_middle_initial as middle_initial',
                    'facultystaff.faculty_department as department',
                    'facultystaff.faculty_unit as unit',
                    'facultystaff.faculty_email as email',
                    'facultystaff.faculty_phone_num as phone',
                    'facultystaff.faculty_profile_image as profile',
                    'facultystaff_records.record_in',
                    'facultystaff_records.record_out',
                    DB::raw('DATE(facultystaff_records.created_at) as date')
                )
                // Only get records from today
                ->whereDate('facultystaff_records.created_at', today())
                // Only faculty who have checked in but not out (record_out is null)
                ->whereNotNull('facultystaff_records.record_in')
                ->whereNull('facultystaff_records.record_out')
                // Search by name, ID, department, or unit
                ->where(function($q) use ($query) {
                    $q->where(DB::raw("CONCAT(facultystaff.faculty_first_name, ' ',
                    CASE
                        WHEN facultystaff.faculty_middle_initial IS NOT NULL AND facultystaff.faculty_middle_initial != ''
                        THEN CONCAT(facultystaff.faculty_middle_initial, '. ')
                        ELSE ''
                    END,
                    facultystaff.faculty_last_name)"), 'LIKE', "%{$query}%")
                        ->orWhere('facultystaff.faculty_id', 'LIKE', "%{$query}%")
                        ->orWhere('facultystaff.faculty_department', 'LIKE', "%{$query}%")
                        ->orWhere('facultystaff.faculty_unit', 'LIKE', "%{$query}%")
                        ->orWhere('facultystaff.faculty_email', 'LIKE', "%{$query}%");
                })
                ->orderBy('facultystaff.faculty_first_name')
                ->limit(10) // Limit to 10 results for performance
                ->get()
                ->map(function($faculty) {
                    return [
                        'id' => $faculty->id,
                        'employee_id' => $faculty->employee_id,
                        'name' => $faculty->name,
                        'first_name' => $faculty->first_name,
                        'last_name' => $faculty->last_name,
                        'middle_initial' => $faculty->middle_initial,
                        'department' => $faculty->department,
                        'unit' => $faculty->unit,
                        'email' => $faculty->email,
                        'phone' => $faculty->phone,
                        'profile' => $faculty->profile ? asset('storage/faculty_profiles/' . $faculty->profile) : null,
                        'record_in' => $faculty->record_in,
                        'record_out' => $faculty->record_out,
                        'date' => $faculty->date,
                        'status' => 'Present' // Since we're only getting those who haven't checked out
                    ];
                });

            return response()->json([
                'faculty' => $faculty,
                'count' => $faculty->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Faculty search error: ' . $e->getMessage());

            return response()->json([
                'faculty' => [],
                'message' => 'An error occurred while searching faculty.',
                'error' => $e->getMessage() // Remove this in production
            ], 500);
        }
    }

    public function facultyLog(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required|exists:facultystaff,faculty_id',
        ]);

        $facultyId = $request->input('faculty_id');
        $now = Carbon::now('Asia/Manila');

        Faculty::where('faculty_id', $facultyId)->first();


        // Get today's latest record
        $latestRecord = FacultyRecords::where('faculty_id', $facultyId)
            ->whereDate('record_in', $now->toDateString())
            ->latest()
            ->first();

        $status = '';

        if (!$latestRecord || $latestRecord->record_out !== null) {
            // Time In
            FacultyRecords::create([
                'faculty_id' => $facultyId,
                'record_in' => $now,
                'record_out' => null,
            ]);
            $status = 'Time In';
            $this->logActivity(
                $request->user()->id ?? null, // Assuming you have authenticated user, use null if not
                $request->user()->role ?? 'System', // Get user role or default to 'System'
                'Faculty/Staff Time In',
                "Faculty/Staff ID: {$facultyId}, Time-In By Guard ID:{$request->user()->id}"
            );
        } else {
            // Time Out
            $latestRecord->update([
                'record_out' => $now,
            ]);
            $status = 'Time Out';
            $this->logActivity(
                $request->user()->id ?? null, // Assuming you have authenticated user, use null if not
                $request->user()->role ?? 'System', // Get user role or default to 'System'
                'Faculty/Staff Time Out',
                "Faculty/Staff ID: {$facultyId}, Time-Out By Guard ID:{$request->user()->id}"
            );
        }

        return response()->json([
            'status' => strtolower($status),
            'time' => $now->toDateTimeString(),
        ]);
    }

    private function logActivity($userId, $role, $action, $description)
    {
        try {
            ActivityLog::create([
                'user_id' => $userId,
                'role' => $role,
                'log_action' => $action,
                'log_description' => $description,
            ]);
        } catch (\Exception $e) {
            // Log to Laravel's default log if activity logging fails
            \Log::error('Failed to create activity log: ' . $e->getMessage());
        }
    }
}
