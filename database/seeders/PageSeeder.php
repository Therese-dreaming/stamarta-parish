<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\User;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first user or create one
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@parish.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        // Create additional admin user for testing
        $adminUser = User::where('email', 'admin@stamarta.com')->first();
        if (!$adminUser) {
            User::create([
                'name' => 'Parish Administrator',
                'email' => 'admin@stamarta.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        $pages = [
            [
                'title' => 'About Our Parish',
                'slug' => 'about',
                'content' => "Our parish has a rich history dating back to [year]. We are committed to living out the Gospel message through worship, education, and service.\n\nOur Mission:\nWe strive to be a welcoming community that celebrates God's love, grows in faith, and serves others with compassion.\n\nOur Vision:\nTo be a beacon of hope and faith in our community, inspiring all to live as disciples of Christ.\n\nOur Values:\n• Faith: Deepening our relationship with God through prayer and worship\n• Community: Building relationships and supporting one another\n• Service: Reaching out to those in need with love and compassion\n• Education: Growing in knowledge and understanding of our faith\n• Stewardship: Caring for God's gifts and using them wisely",
                'meta_title' => 'About Our Parish - History and Mission',
                'meta_description' => 'Learn about our parish history, mission, and values. Discover what makes our community special.',
                'is_published' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ],
            [
                'title' => 'Mass Schedule',
                'slug' => 'mass-schedule',
                'content' => "Join us for Mass and experience the love of God in our community.\n\nRegular Mass Schedule:\n\nSunday Masses:\n• 9:00 AM - Family Mass\n• 11:00 AM - Traditional Mass\n\nWeekday Masses:\n• Monday - Friday: 8:00 AM\n• Saturday: 5:00 PM (Sunday Vigil)\n\nHoly Days of Obligation:\n• 8:00 AM and 7:00 PM\n\nSpecial Masses:\n• First Friday: 7:00 PM (Adoration and Benediction)\n• Feast Days: Check bulletin for times\n\nSacrament of Reconciliation:\n• Saturday: 4:00 PM - 4:45 PM\n• By appointment: Contact parish office\n\nAdoration:\n• First Friday of each month: 7:00 PM - 8:00 PM\n• Every Tuesday: 6:00 PM - 7:00 PM",
                'meta_title' => 'Mass Schedule - Worship Times',
                'meta_description' => 'View our complete Mass schedule including Sunday, weekday, and special Mass times.',
                'is_published' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::create($pageData);
        }
    }
} 