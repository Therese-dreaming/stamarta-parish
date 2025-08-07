<?php

namespace App\Helpers;

use App\Models\Service;

class ScheduleHelper
{
    /**
     * Get formatted schedule for a service
     */
    public static function getFormattedSchedule(Service $service)
    {
        if (empty($service->schedules)) {
            return 'Contact office for schedule';
        }

        $formatted = [];
        $dayNames = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday', 
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];

        foreach ($service->schedules as $day => $times) {
            if (!empty($times)) {
                $formatted[] = $dayNames[$day] . ': ' . implode(', ', $times);
            }
        }

        return implode(' | ', $formatted);
    }

    /**
     * Get available days for a service
     */
    public static function getAvailableDays(Service $service)
    {
        if (empty($service->schedules)) {
            return [];
        }

        $availableDays = [];
        foreach ($service->schedules as $day => $times) {
            if (!empty($times)) {
                $availableDays[] = $day;
            }
        }

        return $availableDays;
    }

    /**
     * Check if service is available on a specific day
     */
    public static function isAvailableOnDay(Service $service, $day)
    {
        return isset($service->schedules[$day]) && !empty($service->schedules[$day]);
    }

    /**
     * Get time slots for a specific day
     */
    public static function getTimeSlotsForDay(Service $service, $day)
    {
        return $service->schedules[$day] ?? [];
    }
} 