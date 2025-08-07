<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ContentService
{
    /**
     * Get a setting value with a default fallback
     */
    public static function getSetting($key, $default = null)
    {
        // For now, return the default values
        // In the future, this could be connected to a database table or config file
        $settings = [
            // Mass Schedule
            'mass_schedule_daily' => 'MON - SAT 6:00AM & 6:00PM',
            'mass_schedule_anticipated' => 'SAT 6:00PM',
            'mass_schedule_sunday_morning' => '5:00 AM, 6:15AM, 7:30 AM, 8:45 AM, 10:00AM',
            'mass_schedule_sunday_afternoon' => '3:00 PM, 4:00 PM, 5:15 PM, 6:30 PM',
            
            // Office Hours
            'office_hours_monday' => 'CLOSED',
            'office_hours_tuesday_saturday' => '8:00 AM - 12:00 NN, 1:00 PM - 5:00 PM',
            'office_hours_sunday' => '8:00 AM - 12:00 NN, 3:00 PM - 5:00 PM',
            
            // Contact Information
            'contact_address' => 'B. Morcilla St.,<br>Pateros, Metro Manila',
            'contact_phone' => '0917-366-4359',
            'contact_email' => 'diocesansaintmartha@gmail.com',
            
            // Social Media (empty by default - will be hidden if not set)
            'facebook_url' => '',
            'youtube_url' => '',
            'instagram_url' => '',
        ];
        
        return $settings[$key] ?? $default;
    }
    
    /**
     * Set a setting value
     */
    public static function setSetting($key, $value)
    {
        // In the future, this could save to database or config file
        // For now, just return true
        return true;
    }
    
    /**
     * Get all settings
     */
    public static function getAllSettings()
    {
        return [
            'mass_schedule_daily' => self::getSetting('mass_schedule_daily'),
            'mass_schedule_anticipated' => self::getSetting('mass_schedule_anticipated'),
            'mass_schedule_sunday_morning' => self::getSetting('mass_schedule_sunday_morning'),
            'mass_schedule_sunday_afternoon' => self::getSetting('mass_schedule_sunday_afternoon'),
            'office_hours_monday' => self::getSetting('office_hours_monday'),
            'office_hours_tuesday_saturday' => self::getSetting('office_hours_tuesday_saturday'),
            'office_hours_sunday' => self::getSetting('office_hours_sunday'),
            'contact_address' => self::getSetting('contact_address'),
            'contact_phone' => self::getSetting('contact_phone'),
            'contact_email' => self::getSetting('contact_email'),
            'facebook_url' => self::getSetting('facebook_url'),
            'youtube_url' => self::getSetting('youtube_url'),
            'instagram_url' => self::getSetting('instagram_url'),
        ];
    }
} 