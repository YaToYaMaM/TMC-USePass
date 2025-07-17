<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use App\Models\ParentCredential;
use App\Imports\StudentsImport;
class StudentController extends Controller
{
    public function store(Request $request)
    {
        $validatedStudent = $request->validate([
            'students_last_name' => 'required',
            'students_first_name' => 'required',
            'students_middle_initial' => 'nullable|string|max:1',
            'students_gender' => 'required',
            'students_id' => 'required|unique:students,students_id',
            'students_program' => 'required',
            'students_major' => 'nullable',
            'students_unit' => 'required',
            'students_email' => 'required|email|unique:students,students_email',
            'students_phone_num' => 'required',
            'students_profile_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('students_profile_image')) {
            $validatedStudent['students_profile_image'] = $request->file('students_profile_image')->store('students', 'public');
        }

        // Save student
        $student = Student::create($validatedStudent);

        // Validate parent data
        $validatedParent = $request->validate([
            'parent_last_name' => 'required',
            'parent_first_name' => 'required',
            'parent_middle_initial' => 'nullable|string|max:1',
            'parent_phone_num' => 'required',
            'parent_email' => 'required|email|unique:parents,parent_email',
            'parent_relation' => 'required',
        ]);

        // Link student to parent using students_id
        $validatedParent['students_id'] = $student->students_id;

        // Save parent
        ParentCredential::create($validatedParent);

        return response()->json(['message' => 'Saved successfully.']);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new StudentsImport, $request->file('file'));

        return response()->json(['message' => 'Import successful']);
    }
    public function index()
    {
        $students = Student::all(); // You can paginate here if needed
        return response()->json($students);
    }

}
