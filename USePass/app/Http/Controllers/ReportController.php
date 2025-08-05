<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function incident(Request $request)
    {
        $user = auth()->user();

        // Admins see all, guards see only their own
        $reports = $user->role === 'admin'
            ? IncidentReport::with('user')->orderBy('created_at', 'desc')->get()
            : IncidentReport::with('user')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        // Add guard name to each report for display
        $reports = $reports->map(function ($report) {
            $report->guard_name = $report->user ?
                $report->user->first_name . ' ' . $report->user->last_name :
                'Unknown Guard';
            return $report;
        });

        // Check if this is an AJAX/API request
        if ($request->expectsJson()) {
            return response()->json($reports);
        }

        // Render different views based on user role
        if ($user->role === 'admin') {
            return Inertia::render('Frontend/secIncident', [
                'reports' => $reports,
            ]);
        } else {
            return Inertia::render('Frontend/guardHome', [
                'reports' => $reports,
            ]);
        }
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'date' => 'required|date',
            'what' => 'required|string',
            'who' => 'required|string',
            'where' => 'required|string',
            'when' => 'required|string',
            'how' => 'required|string',
            'why' => 'required|string',
            'recommendation' => 'required|string',
        ]);

        // Create the incident report
        $report = IncidentReport::create([
            'user_id' => auth()->id(),
            'description' => $validated['description'],
            'type' => $validated['type'],
            'date' => $validated['date'],
            'what' => $validated['what'],
            'who' => $validated['who'],
            'where' => $validated['where'],
            'when' => $validated['when'],
            'how' => $validated['how'],
            'why' => $validated['why'],
            'recommendation' => $validated['recommendation'],
        ]);

        // For Inertia requests, redirect back to the same page
        return redirect()->back()->with('success', 'Incident report created successfully!');
    }
    public function print(Request $request)
    {
        $reportData = $request->only([
            'id', 'name', 'type', 'date', 'what', 'who',
            'where', 'when', 'how', 'why', 'recommendation'
        ]);

        return view('reports.print', compact('reportData'));
    }
}
