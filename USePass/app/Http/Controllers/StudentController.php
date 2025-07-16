<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use App\Models\ParentCredential;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'middle_initial' => 'nullable|string|max:1',
            'gender' => 'required',
            'id' => 'required|unique:students',
            'program' => 'required',
            'major' => 'nullable',
            'unit' => 'required',
            'email' => 'required|email|unique:students',
            'contact_number' => 'required',
            'image' => 'nullable|image|max:5120',

            // Parent
            'parent_last_name' => 'required|string',
            'parent_first_name' => 'required|string',
            'parent_middle_initial' => 'nullable|string|max:1',
            'parent_contact' => 'required|string',
            'parent_email' => 'required|email|unique:students',
            'parent_relation' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('students', 'public');
        }

        // Save student first
        $student = Student::create([
            'last_name' => $validated['last_name'],
            'first_name' => $validated['first_name'],
            'middle_initial' => $validated['middle_initial'],
            'gender' => $validated['gender'],
            'id_number' => $validated['id_number'],
            'program' => $validated['program'],
            'major' => $validated['major'],
            'unit' => $validated['unit'],
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'],
            'image' => $validated['image'] ?? null,
        ]);

        // Then save parent, linking the student_id
        ParentCredential::create([
            'student_id' => $student->id,
            'last_name' => $validated['parent_last_name'],
            'first_name' => $validated['parent_first_name'],
            'middle_initial' => $validated['parent_middle_initial'],
            'contact_number' => $validated['parent_contact'],
            'relationship' => $validated['relationship'],
        ]);
        dd($validated);
        return redirect()->back()->with('success', 'Student and parent saved successfully.');
    }
}
