<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Service;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if Mass Intention service already exists
        $existingService = Service::where('service_type', 'mass_intention')->first();
        
        if (!$existingService) {
            Service::create([
                'name' => 'Mass Intention',
                'description' => 'Request a mass to be offered for a specific intention - Thanksgiving Mass, Special Intentions, or Repose of the Soul. Multiple souls can be included for Repose of the Soul intentions.',
                'duration_minutes' => 60,
                'max_slots' => 999,
                'is_active' => true,
                'service_type' => 'mass_intention',
                'requirements' => [],
                'fees' => [
                    [
                        'type' => 'regular',
                        'description' => 'Mass Intention Fee',
                        'amount' => 200.00
                    ]
                ],
                'schedules' => [
                    'monday' => ['6:00 AM', '6:00 PM'],
                    'tuesday' => ['6:00 AM', '6:00 PM'],
                    'wednesday' => ['6:00 AM', '6:00 PM'],
                    'thursday' => ['6:00 AM', '6:00 PM'],
                    'friday' => ['6:00 AM', '6:00 PM'],
                    'saturday' => ['6:00 AM', '6:00 PM'],
                    'sunday' => ['6:00 AM', '7:30 AM', '8:45 AM', '10:00 AM', '3:00 PM', '4:00 PM', '5:15 PM', '6:30 PM']
                ],
                'booking_restrictions' => [
                    'minimum_days' => 1,
                    'maximum_days' => 180
                ],
                'notes' => 'Mass intentions are available for Thanksgiving Mass, Special Intentions, and Repose of the Soul. Minimum fee of ₱200 regardless of the number of souls included. No limit on the number of bookings per time slot.'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Service::where('service_type', 'mass_intention')->delete();
    }
};
