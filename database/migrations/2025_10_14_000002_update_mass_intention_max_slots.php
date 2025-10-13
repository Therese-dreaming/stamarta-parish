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
        // Update Mass Intention service with new specifications
        Service::where('service_type', 'mass_intention')->update([
            'description' => 'Request a mass to be offered for a specific intention - Thanksgiving Mass, Special Intentions, or Repose of the Soul. Multiple souls can be included for Repose of the Soul intentions.',
            'max_slots' => 999,
            'requirements' => json_encode([]),
            'fees' => json_encode([
                [
                    'type' => 'regular',
                    'description' => 'Mass Intention Fee',
                    'amount' => 200.00
                ]
            ]),
            'schedules' => json_encode([
                'monday' => ['6:00 AM', '6:00 PM'],
                'tuesday' => ['6:00 AM', '6:00 PM'],
                'wednesday' => ['6:00 AM', '6:00 PM'],
                'thursday' => ['6:00 AM', '6:00 PM'],
                'friday' => ['6:00 AM', '6:00 PM'],
                'saturday' => ['6:00 AM', '6:00 PM'],
                'sunday' => ['6:00 AM', '7:30 AM', '8:45 AM', '10:00 AM', '3:00 PM', '4:00 PM', '5:15 PM', '6:30 PM']
            ]),
            'notes' => 'Mass intentions are available for Thanksgiving Mass, Special Intentions, and Repose of the Soul. Minimum fee of ₱200 regardless of the number of souls included. No limit on the number of bookings per time slot.'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous values if rolled back
        Service::where('service_type', 'mass_intention')->update([
            'description' => 'Request a mass to be offered for a specific intention - thanksgiving, departed souls, healing, petitions, and other special intentions.',
            'max_slots' => 10,
            'requirements' => json_encode(['Valid ID']),
            'fees' => json_encode([
                [
                    'type' => 'regular',
                    'description' => 'Mass Intention Fee',
                    'amount' => 500.00
                ]
            ]),
            'schedules' => json_encode([
                'monday' => ['6:00 AM', '6:00 PM'],
                'tuesday' => ['6:00 AM', '6:00 PM'],
                'wednesday' => ['6:00 AM', '6:00 PM'],
                'thursday' => ['6:00 AM', '6:00 PM'],
                'friday' => ['6:00 AM', '6:00 PM'],
                'saturday' => ['6:00 AM', '6:00 PM'],
                'sunday' => ['6:00 AM', '8:00 AM', '10:00 AM', '6:00 PM']
            ]),
            'notes' => 'Mass intentions can be offered for various purposes including thanksgiving, souls of the departed, healing, safe travel, and other special intentions. Multiple intentions can be scheduled for the same mass.'
        ]);
    }
};
