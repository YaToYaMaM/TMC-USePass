<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Faculty;
use Maatwebsite\Excel\Facades\Excel;

class FacultyController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'faculty_id' => 'required|string|unique:facultyStaff,faculty_id',
            'faculty_first_name' => 'required|string|max:100',
            'faculty_last_name' => 'required|string|max:100',
            'faculty_middle_initial' => 'nullable|string|max:1',
            'faculty_gender' => 'required|string|max:50',
            'faculty_department' => 'required|string|max:100',
            'faculty_unit' => 'required|in:Tagum,Mabini',
            'faculty_email' => 'required|email|unique:facultyStaff,faculty_email|max:100',
            'faculty_phone_num' => 'required|string|max:20',
            'faculty_profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $imagePath = null;
        if ($request->hasFile('faculty_profile_image')) {
            $image = $request->file('faculty_profile_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('profile_pictures');

            // Create the folder if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);
            $imagePath = 'faculty_pictures/' . $imageName; // Relative path to store in DB
        }
        Faculty::create([
            'faculty_id' => $request->faculty_id,
            'faculty_last_name' => $request->faculty_last_name,
            'faculty_first_name' => $request->faculty_first_name,
            'faculty_middle_initial' => $request->faculty_middle_initial,
            'faculty_gender'=> $request->faculty_gender,
            'faculty_department'=> $request->faculty_department,
            'faculty_unit'=> $request->faculty_unit,
            'faculty_email'=> $request->faculty_email,
            'faculty_phone_num' => $request->faculty_phone_num,
            'faculty_profile_image' => $imagePath,
        ]);

        $this->logActivity(
            $request->user()->id ?? null, // Assuming you have authenticated user, use null if not
            $request->user()->role ?? 'System', // Get user role or default to 'System'
            'Faculty/Staff Created',
            "New Faculty/Staff Added: ID: {$request->faculty_id}, {$request->faculty_first_name} {$request->faculty_last_name}"
        );

        return response()->json(['message' => 'Guard created successfully.']);

    }

    public function index(){
        $faculty = Faculty::all();
        return response()->json($faculty);

    }

    public function showRegistrationForm()
    {
        $generatedFacultyId = $this->generateFacultyId();

        return inertia('Frontend/FacultyRegistration', [
            'generatedFacultyId' => $generatedFacultyId
        ]);
    }

    public function fetchFacultyProfile($faculty_id)
    {
        $faculty = Faculty::select(
            'faculty_id as id',
            \DB::raw("CONCAT(faculty_first_name, ' ', faculty_middle_initial, ' ', faculty_last_name) as fullName"),
            'faculty_department as program',
            'faculty_profile_image as profileImage'
        )->where('faculty_id', $faculty_id)->first();

        return response()->json([
            'exists' => $faculty !== null,
            'faculty' => $faculty,
        ]);
    }

    public function fetchForQR($faculty_id_or_name)
    {
        try {
            $faculty = null;

            // First try to find by faculty_id
            $faculty = Faculty::where('faculty_id', $faculty_id_or_name)->first();

            // If not found by ID, try searching by name
            if (!$faculty) {
                // Search by concatenated full name
                $faculty = Faculty::whereRaw("CONCAT(faculty_first_name, ' ', COALESCE(CONCAT(faculty_middle_initial, '. '), ''), faculty_last_name) LIKE ?", ["%$faculty_id_or_name%"])
                    ->first();

                // If still not found, try more flexible name search
                if (!$faculty) {
                    $nameParts = explode(' ', trim($faculty_id_or_name));

                    $query = Faculty::query();
                    foreach ($nameParts as $part) {
                        if (!empty($part)) {
                            $query->where(function($q) use ($part) {
                                $q->where('faculty_first_name', 'LIKE', "%$part%")
                                    ->orWhere('faculty_last_name', 'LIKE', "%$part%")
                                    ->orWhere('faculty_middle_initial', 'LIKE', "%$part%");
                            });
                        }
                    }

                    $faculty = $query->first();
                }
            }

            if ($faculty) {
                return response()->json([
                    'success' => true,
                    'faculty' => $faculty
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Faculty not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving faculty data'
            ], 500);
        }
    }

    public function searchSuggestions(Request $request)
    {
        try {
            $name = $request->get('name');
            $limit = $request->get('limit', 5);

            if (strlen($name) < 2) {
                return response()->json([
                    'success' => true,
                    'faculty' => []
                ]);
            }

            // Search for suggestions
            $nameParts = explode(' ', trim($name));
            $query = Faculty::query();

            foreach ($nameParts as $part) {
                if (!empty($part)) {
                    $query->where(function($q) use ($part) {
                        $q->where('faculty_first_name', 'LIKE', "%$part%")
                            ->orWhere('faculty_last_name', 'LIKE', "%$part%")
                            ->orWhere('faculty_middle_initial', 'LIKE', "%$part%");
                    });
                }
            }

            $faculty = $query->limit($limit)->get();

            return response()->json([
                'success' => true,
                'faculty' => $faculty
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching faculty'
            ], 500);
        }
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

    private function generateFacultyId()
    {
        $year = date('Y');
        $prefix = "USePass-{$year}-";

        // Get the last faculty ID for this year
        $lastFaculty = Faculty::where('faculty_id', 'LIKE', "{$prefix}%")
            ->orderBy('faculty_id', 'desc')
            ->first();

        if ($lastFaculty) {
            // Extract the sequential number and increment
            $lastNumber = (int) substr($lastFaculty->faculty_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            // Start from 0001 for this year
            $newNumber = 1;
        }

        // Format with leading zeros (4 digits)
        $sequentialNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return $prefix . $sequentialNumber;
    }
}
