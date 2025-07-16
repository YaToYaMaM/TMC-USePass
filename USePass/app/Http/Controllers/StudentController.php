<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_last_name' => 'required',
            'student_first_name' => 'required',
            'student_middle_initial' => 'nullable|string|max:1',
            'student_gender' => 'required',
            'id_number' => 'required|unique:students',
            'student_program' => 'required',
            'student_major' => 'nullable',
            'student_unit' => 'required',
            'student_email' => 'required|email|unique:students',
            'student_phone_number' => 'required',
            'student_profile_image' => 'nullable|image|max:5120', // 5MB max
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('students', 'public');
        }

        Student::create($validated);

        return redirect()->back()->with('success', 'Student saved.');
    }
}
