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
                "Faculty/Staff ID: {$facultyId->faculty_id}, Time-In By Guard ID:{$request->user()->id}"
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
                "Faculty/Staff ID: {$facultyId->faculty_id}, Time-Out By Guard ID:{$request->user()->id}"
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
