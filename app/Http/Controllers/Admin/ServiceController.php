<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('name')->get();
        return view('admin.services.index', compact('services'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'max_slots' => 'required|integer|min:1|max:100',
            'requirements' => 'nullable|array',
            'requirements.*' => 'string|max:255',
            'fee_types' => 'nullable|array',
            'fee_types.*' => 'string|max:255',
            'fee_descriptions' => 'nullable|array',
            'fee_descriptions.*' => 'string|max:255',
            'fee_amounts' => 'nullable|array',
            'fee_amounts.*' => 'numeric|min:0',
            'fee_min_days' => 'nullable|array',
            'fee_min_days.*' => 'integer|min:0|max:365',
            'fee_max_days' => 'nullable|array',
            'fee_max_days.*' => 'integer|min:0|max:365',
            'schedules' => 'nullable|array',
            'schedule_ampm' => 'nullable|array',
            'booking_restrictions' => 'nullable|array',
            'booking_restrictions.minimum_days' => 'required|integer|min:1|max:365',
            'booking_restrictions.maximum_days' => 'required|integer|min:1|max:365',
            'notes' => 'nullable|string',
        ]);

        // Process requirements
        $requirements = $request->input('requirements', []);
        $requirements = array_filter($requirements, function($req) {
            return !empty(trim($req));
        });

        // Process fees with new structure
        $fees = [];
        $feeTypes = $request->input('fee_types', []);
        $feeDescriptions = $request->input('fee_descriptions', []);
        $feeAmounts = $request->input('fee_amounts', []);
        $feeMinDays = $request->input('fee_min_days', []);
        $feeMaxDays = $request->input('fee_max_days', []);
        
        for ($i = 0; $i < count($feeTypes); $i++) {
            if (!empty(trim($feeTypes[$i])) && isset($feeAmounts[$i])) {
                $feeData = [
                    'amount' => (float) $feeAmounts[$i],
                    'description' => trim($feeDescriptions[$i] ?? $feeTypes[$i])
                ];

                // Add condition if min/max days are set
                if (!empty($feeMinDays[$i]) || !empty($feeMaxDays[$i])) {
                    $condition = [];
                    if (!empty($feeMinDays[$i])) {
                        $condition['min_days'] = (int) $feeMinDays[$i];
                    }
                    if (!empty($feeMaxDays[$i])) {
                        $condition['max_days'] = (int) $feeMaxDays[$i];
                    }
                    $feeData['condition'] = $condition;
                }

                $fees[trim($feeTypes[$i])] = $feeData;
            }
        }

        // Process schedules
        $schedules = [];
        $scheduleData = $request->input('schedules', []);
        $scheduleAmpm = $request->input('schedule_ampm', []);
        
        foreach ($scheduleData as $day => $times) {
            if (!empty($times)) {
                $daySchedules = [];
                foreach ($times as $index => $time) {
                    if (!empty(trim($time))) {
                        $ampm = $scheduleAmpm[$day][$index] ?? 'AM';
                        $daySchedules[] = trim($time) . ' ' . $ampm;
                    }
                }
                if (!empty($daySchedules)) {
                    $schedules[$day] = $daySchedules;
                }
            }
        }

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'duration_minutes' => $validated['duration_minutes'],
            'max_slots' => $validated['max_slots'],
            'requirements' => $requirements,
            'fees' => $fees,
            'schedules' => $schedules,
            'booking_restrictions' => $validated['booking_restrictions'],
            'notes' => $validated['notes'],
        ];

        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function toggleStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        
        $status = $service->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.services.index')
            ->with('success', "Service {$status} successfully.");
    }
} 