<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ParochialActivity;
use App\Models\User;

class UpdateParochialActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user or create one if none exists
        $adminUser = User::where('role', 'admin')->first();
        
        if (!$adminUser) {
            $adminUser = User::first(); // Fallback to any user
        }

        if ($adminUser) {
            // Update all existing parochial activities that don't have created_by
            ParochialActivity::whereNull('created_by')
                ->update([
                    'created_by' => $adminUser->id,
                    'updated_by' => $adminUser->id,
                ]);
        }
    }
}
