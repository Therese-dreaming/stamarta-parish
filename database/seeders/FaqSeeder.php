<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;
use App\Models\User;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user or create one
        $admin = User::where('role', 'admin')->first();
        $createdBy = $admin ? $admin->id : 1;

        $faqs = [
            // Booking FAQs
            [
                'question' => 'How do I book a service?',
                'answer' => 'To book a service, you need to register an account first. Then go to the Services page, select the service you want, and follow the booking process. You can choose your preferred date and time slot.',
                'category' => 'booking',
                'keywords' => ['book', 'booking', 'reserve', 'reservation', 'schedule', 'appointment', 'arrange', 'service', 'services'],
                'order' => 1,
                'is_active' => true
            ],
            [
                'question' => 'What documents do I need for booking?',
                'answer' => 'For most services, you will need a valid ID (passport, driver\'s license, or government ID). For specific services like baptism or marriage, additional documents may be required. Please check the service details for specific requirements.',
                'category' => 'booking',
                'keywords' => ['documents', 'requirements', 'ID', 'papers', 'needed', 'document', 'identification', 'valid'],
                'order' => 2,
                'is_active' => true
            ],
            [
                'question' => 'Can I cancel my booking?',
                'answer' => 'Yes, you can cancel your booking up to 24 hours before the scheduled time. Go to your "My Bookings" page and click the cancel button. Please note that some services may have different cancellation policies.',
                'category' => 'booking',
                'keywords' => ['cancel', 'cancellation', 'refund', 'change', 'modify', 'canceled', 'cancelled'],
                'order' => 3,
                'is_active' => true
            ],
            [
                'question' => 'How much does a service cost?',
                'answer' => 'Service costs vary depending on the type of service. Regular fees are listed on each service page. For specific pricing, please check the service details or contact the parish office.',
                'category' => 'services',
                'keywords' => ['cost', 'price', 'fee', 'payment', 'how much', 'costs', 'prices', 'fees', 'pay'],
                'order' => 4,
                'is_active' => true
            ],
            [
                'question' => 'What services are available?',
                'answer' => 'We offer various religious services including baptism, confirmation, marriage, funeral masses, and special blessings. Each service has specific requirements and schedules. Visit our Services page to see all available options.',
                'category' => 'services',
                'keywords' => ['services', 'available', 'offer', 'types', 'what', 'service', 'ceremony', 'mass', 'blessing'],
                'order' => 5,
                'is_active' => true
            ],
            [
                'question' => 'Do you offer urgent services?',
                'answer' => 'For urgent service requests, please contact the parish office directly. We will do our best to accommodate urgent cases based on availability and scheduling.',
                'category' => 'services',
                'keywords' => ['urgent', 'emergency', 'fast', 'quick', 'immediate', 'service', 'services'],
                'order' => 6,
                'is_active' => true
            ],
            [
                'question' => 'What are your office hours?',
                'answer' => 'Our parish office is open Monday to Friday from 8:00 AM to 5:00 PM, and Saturday from 8:00 AM to 12:00 PM. We are closed on Sundays and holidays. For urgent matters outside office hours, please call our emergency contact.',
                'category' => 'general',
                'keywords' => ['hours', 'office', 'open', 'closed', 'schedule', 'time', 'available', 'hour'],
                'order' => 7,
                'is_active' => true
            ],
            [
                'question' => 'How can I contact the parish?',
                'answer' => 'You can contact us by phone at 0917-366-4359, by email at diocesansaintmartha@gmail.com, or visit our office during business hours. We also have a contact form on our website for general inquiries.',
                'category' => 'general',
                'keywords' => ['contact', 'phone', 'email', 'reach', 'call', 'message', 'inquiry'],
                'order' => 8,
                'is_active' => true
            ],
            [
                'question' => 'Where is the church located?',
                'answer' => 'Our church is located at B. Morcilla St., Pateros, Metro Manila. We are easily accessible by public transportation and have parking available for visitors.',
                'category' => 'general',
                'keywords' => ['location', 'address', 'where', 'map', 'directions', 'place', 'church'],
                'order' => 9,
                'is_active' => true
            ],
            [
                'question' => 'How do I pay for services?',
                'answer' => 'We accept various payment methods including cash, bank transfer, and digital payments. Payment instructions will be provided after your booking is confirmed. For online payments, we accept GCash and bank transfers.',
                'category' => 'payment',
                'keywords' => ['pay', 'payment', 'cash', 'transfer', 'gcash', 'money', 'paid', 'paying', 'cost', 'fee', 'price'],
                'order' => 10,
                'is_active' => true
            ],
            [
                'question' => 'What are the mass schedules?',
                'answer' => 'We have regular masses on weekdays at 6:00 AM and 6:00 PM, and on Sundays at 6:00 AM, 8:00 AM, 10:00 AM, 4:00 PM, and 6:00 PM. Special masses are held on holy days and special occasions.',
                'category' => 'schedule',
                'keywords' => ['mass', 'schedule', 'time', 'sunday', 'weekday', 'masses', 'service', 'worship'],
                'order' => 11,
                'is_active' => true
            ],
            [
                'question' => 'Can I request a special blessing?',
                'answer' => 'Yes, you can request special blessings for various occasions. Please contact the parish office to schedule an appointment with one of our priests. Special blessings may include house blessings, vehicle blessings, and other religious ceremonies.',
                'category' => 'services',
                'keywords' => ['blessing', 'special', 'request', 'house', 'vehicle', 'bless', 'blessed', 'ceremony'],
                'order' => 12,
                'is_active' => true
            ]
        ];

        foreach ($faqs as $faq) {
            Faq::create([
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'category' => $faq['category'],
                'keywords' => $faq['keywords'],
                'order' => $faq['order'],
                'is_active' => $faq['is_active'],
                'created_by' => $createdBy
            ]);
        }
    }
} 