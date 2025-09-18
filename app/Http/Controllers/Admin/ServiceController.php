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
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.services.index', compact('services', 'isStaff'));
    }

    public function show(Service $service)
    {
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.services.show', compact('service', 'isStaff'));
    }

    public function edit(Service $service)
    {
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.services.edit', compact('service', 'isStaff'));
    }

    public function update(Request $request, Service $service)
    {
        // Debug logging
        \Log::info('Service update request received', [
            'service_id' => $service->id,
            'request_data' => $request->all(),
            'method' => $request->method()
        ]);
        
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
            'fee_amounts' => 'required|array',
            'fee_amounts.*' => 'required|numeric|min:0',
            'fee_min_days' => 'nullable|array',
            'fee_min_days.*' => 'nullable|string',
            'fee_max_days' => 'nullable|array',
            'fee_max_days.*' => 'nullable|string',
            'schedules' => 'nullable|array',
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

        // Process simplified fee structure
        $fees = [];
        $feeAmounts = $request->input('fee_amounts', []);
        
        // Always set regular fee with the provided amount
        if (isset($feeAmounts[0]) && is_numeric($feeAmounts[0])) {
            $fees['regular'] = [
                'amount' => (float) $feeAmounts[0],
                'description' => 'Standard service fee'
            ];
        }

        // Process schedules
        $schedules = [];
        $scheduleData = $request->input('schedules', []);
        
        foreach ($scheduleData as $day => $times) {
            if (!empty($times)) {
                $daySchedules = [];
                foreach ($times as $time) {
                    if (!empty(trim($time))) {
                        // Convert 24-hour format to 12-hour format with AM/PM
                        $carbonTime = \Carbon\Carbon::createFromFormat('H:i', trim($time));
                        $daySchedules[] = $carbonTime->format('g:i A');
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

        try {
            $service->update($data);
            
            \Log::info('Service updated successfully', ['service_id' => $service->id]);
            
            return redirect()->route('admin.services.index')
                ->with('success', 'Service updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Service update failed', [
                'service_id' => $service->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update service: ' . $e->getMessage()]);
        }
    }

    public function toggleStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        
        $status = $service->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.services.index')
            ->with('success', "Service {$status} successfully.");
    }
} 