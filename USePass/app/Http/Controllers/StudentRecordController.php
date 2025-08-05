<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        $now = \Carbon\Carbon::now();

        // Get today’s latest record
        $latestRecord = StudentRecord::where('students_id', $studentsId)
            ->whereDate('record_in', $now->toDateString())
            ->latest()
            ->first();

        if (!$latestRecord || $latestRecord->record_out !== null) {
            // No scan today or last record already has time-out → TIME IN
            StudentRecord::create([
                'students_id' => $studentsId,
                'record_in' => $now,
                'record_out' => null,
            ]);

            return response()->json([
                'status' => 'in',
                'time' => $now->toDateTimeString(),
            ]);
        } else {
            // Last record has time-in but no time-out → TIME OUT
            $latestRecord->update([
                'record_out' => $now,
            ]);

            return response()->json([
                'status' => 'out',
                'time' => $now->toDateTimeString(),
            ]);
        }
    }




}
