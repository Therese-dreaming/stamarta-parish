<?php

namespace App\Services;

use App\Models\ParishSetting;
use Illuminate\Support\Facades\Log;

class ParishBudgetService
{
    /**
     * Add amount to parish total budget
     */
    public static function addToParishBudget(float $amount, string $description = 'Budget addition'): bool
    {
        try {
            $parishSetting = ParishSetting::where('key', 'parish_total_budget')->first();
            
            if ($parishSetting) {
                $currentBudget = (float) $parishSetting->value;
                $parishSetting->update([
                    'value' => $currentBudget + $amount
                ]);
            } else {
                ParishSetting::create([
                    'key' => 'parish_total_budget',
                    'value' => $amount,
                    'description' => 'Total parish budget from all sources'
                ]);
            }
            
            Log::info("Parish budget increased by ₱{$amount}. Reason: {$description}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to add ₱{$amount} to parish budget: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Subtract amount from parish total budget
     */
    public static function subtractFromParishBudget(float $amount, string $description = 'Budget deduction'): bool
    {
        try {
            $parishSetting = ParishSetting::where('key', 'parish_total_budget')->first();
            
            if ($parishSetting) {
                $currentBudget = (float) $parishSetting->value;
                $parishSetting->update([
                    'value' => $currentBudget - $amount
                ]);
                
                Log::info("Parish budget decreased by ₱{$amount}. Reason: {$description}");
                return true;
            } else {
                Log::warning("Attempted to subtract ₱{$amount} from parish budget, but parish_total_budget setting doesn't exist");
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Failed to subtract ₱{$amount} from parish budget: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get current parish total budget
     */
    public static function getParishTotalBudget(): float
    {
        $parishSetting = ParishSetting::where('key', 'parish_total_budget')->first();
        return $parishSetting ? (float) $parishSetting->value : 0;
    }
    
    /**
     * Set parish total budget to specific amount
     */
    public static function setParishBudget(float $amount, string $description = 'Budget set'): bool
    {
        try {
            $parishSetting = ParishSetting::where('key', 'parish_total_budget')->first();
            
            if ($parishSetting) {
                $parishSetting->update(['value' => $amount]);
            } else {
                ParishSetting::create([
                    'key' => 'parish_total_budget',
                    'value' => $amount,
                    'description' => 'Total parish budget from all sources'
                ]);
            }
            
            Log::info("Parish budget set to ₱{$amount}. Reason: {$description}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to set parish budget to ₱{$amount}: " . $e->getMessage());
            return false;
        }
    }
}
