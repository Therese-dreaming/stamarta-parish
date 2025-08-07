<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Priest;

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
                'specializations' => json_encode(['Baptism', 'Wedding', 'Funeral', 'Mass']),
            ],
            [
                'name' => 'Rev. Fr. Loreto N. Sanchez, Jr.',
                'email' => 'fr.sanchez@stamartaparish.com',
                'phone' => '0918-234-5678',
                'bio' => 'Assistant Parish Priest dedicated to serving the spiritual needs of the parishioners.',
                'is_active' => true,
                'photo_path' => 'Rev. Fr. Loreto N. Sanchez, Jr..png',
                'specializations' => json_encode(['Baptism', 'Wedding', 'Confession', 'Mass']),
            ],
            [
                'name' => 'Rev. Fr. Orlando B. Cantillon',
                'email' => 'fr.cantillon@stamartaparish.com',
                'phone' => '0919-345-6789',
                'bio' => 'Parochial Vicar committed to pastoral care and community outreach programs.',
                'is_active' => true,
                'photo_path' => 'Rev. Fr. Orlando B. Cantillon.JPG',
                'specializations' => json_encode(['Baptism', 'Blessing', 'Mass', 'Community Outreach']),
            ],
        ];

        foreach ($priests as $priest) {
            Priest::create($priest);
        }
    }
}
