<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Priest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PriestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priests = [
            [
                'name' => 'Rev. Fr. Jorge Jesus A. Bellosillo',
                'email' => 'fr.bellosillo@stamartaparish.com',
                'phone' => '0917-123-4567',
                'bio' => 'Parish Priest of Sta. Marta Parish with over 15 years of service to the community.',
                'is_active' => true,
                'photo_path' => 'Rev. Fr. Jorge Jesus A. Bellosillo.JPG',
                'specializations' => ['Baptism', 'Wedding', 'Funeral', 'Mass'],
                'password' => 'password',
            ],
            [
                'name' => 'Rev. Fr. Loreto N. Sanchez, Jr.',
                'email' => 'fr.sanchez@stamartaparish.com',
                'phone' => '0918-234-5678',
                'bio' => 'Assistant Parish Priest dedicated to serving the spiritual needs of the parishioners.',
                'is_active' => true,
                'photo_path' => 'Rev. Fr. Loreto N. Sanchez, Jr..png',
                'specializations' => ['Baptism', 'Wedding', 'Confession', 'Mass'],
                'password' => 'password',
            ],
            [
                'name' => 'Rev. Fr. Orlando B. Cantillon',
                'email' => 'fr.cantillon@stamartaparish.com',
                'phone' => '0919-345-6789',
                'bio' => 'Parochial Vicar committed to pastoral care and community outreach programs.',
                'is_active' => true,
                'photo_path' => 'Rev. Fr. Orlando B. Cantillon.JPG',
                'specializations' => ['Baptism', 'Blessing', 'Mass', 'Community Outreach'],
                'password' => 'password',
            ],
        ];

        foreach ($priests as $priestData) {
            // Extract password for user creation
            $password = $priestData['password'];
            unset($priestData['password']);

            // Check if user already exists
            $user = User::where('email', $priestData['email'])->first();
            
            if (!$user) {
                // Create user account
                $user = User::create([
                    'name' => $priestData['name'],
                    'email' => $priestData['email'],
                    'password' => Hash::make($password),
                    'role' => 'priest',
                    'email_verified_at' => now(),
                ]);
            }

            // Find or create priest record
            $priest = Priest::where('email', $priestData['email'])->first();
            
            if (!$priest) {
                // Create priest record
                $priest = Priest::create($priestData);
            }
            
            // Link priest to user if not already linked
            if (!$priest->user_id) {
                $priest->update(['user_id' => $user->id]);
            }
        }
    }
}
