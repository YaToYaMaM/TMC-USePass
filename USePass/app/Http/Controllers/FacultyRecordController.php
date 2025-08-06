<?php

namespace App\Http\Controllers;

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
        $query = DB::table('faculty_staff_records')
            ->join('facultystaff', 'facultystaff.faculty_id', '=', 'faculty_staff_records.faculty_id')
            ->select(
                'facultystaff.faculty_id as faculty_id',
                DB::raw("CONCAT(facultystaff.faculty_first_name, ' ', facultystaff.faculty_middle_initial, '. ', facultystaff.faculty_last_name) as name"),
                'faculty.faculty_department',
                'facultystaff.faculty_unit',
                DB::raw('DATE(faculty_staff_records.created_at) as date'),
                'faculty_staff_records.record_in',
                'faculty_staff_records.record_out'
            );

        if ($request->filled('date')) {
            $query->whereDate('faculty_staff_records.created_at', $request->date);
        }

        if ($request->filled('department')) {
            $query->where('facultystaff.faculty_department', $request->dapartment);
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
        } else {
            // Time Out
            $latestRecord->update([
                'record_out' => $now,
            ]);
            $status = 'Time Out';
        }

        return response()->json([
            'status' => strtolower($status),
            'time' => $now->toDateTimeString(),
        ]);
    }
}
