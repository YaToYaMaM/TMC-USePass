<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StudentRecordController extends Controller
{
    public function fetchRecords(Request $request)
    {
        $date = $request->input('date');

        $records = DB::table('students_records')
            ->join('students', 'students.id', '=', 'students_records.students_id')
            ->select(
                'students.id as student_id',
                DB::raw("CONCAT(students.first_name, ' ', students.middle_initial, '. ', students.last_name) as name"),
                'students.program',
                'students.major',
                DB::raw('DATE(students_records.created_at) as date'),
                'students_records.record_in',
                'students_records.record_out'
            )
            ->when($date, function ($query) use ($date) {
                $query->whereDate('students_records.created_at', $date);
            })
            ->get();

        return response()->json($records);
    }
}
