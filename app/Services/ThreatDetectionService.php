<?php

namespace App\Services;

use App\Models\AuditLog;

class ThreatDetectionService
{
    public static function run()
    {
        $alerts = [];

        // 🚨 DELETE spike
        $deleteCount = AuditLog::where('action', 'DELETE')
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();

        if ($deleteCount >= 10) {
            $alerts[] = [
                'level' => 'HIGH',
                'message' => 'Mass deletion detected',
                'details' => "$deleteCount delete actions in 10 minutes"
            ];
        } elseif ($deleteCount >= 5) {
            $alerts[] = [
                'level' => 'MEDIUM',
                'message' => 'Elevated deletion activity',
                'details' => "$deleteCount delete actions detected"
            ];
        }

        // 🚨 SIGINT activity
        $sigintCount = AuditLog::where('module', 'SIGINT')
            ->whereDate('created_at', today())
            ->count();

        if ($sigintCount > 100) {
            $alerts[] = [
                'level' => 'HIGH',
                'message' => 'SIGINT Threat Detected',
                'details' => "$sigintCount signals detected"
            ];
        } elseif ($sigintCount > 50) {
            $alerts[] = [
                'level' => 'MEDIUM',
                'message' => 'Unusual SIGINT Activity',
                'details' => "$sigintCount signals detected"
            ];
        } elseif ($sigintCount > 10) {
            $alerts[] = [
                'level' => 'LOW',
                'message' => 'SIGINT Activity Notice',
                'details' => "$sigintCount signals detected"
            ];
        }

        return $alerts;
    }

    public static function score()
    {
        $score = 0;

        // 🔢 SIGNAL VOLUME (SIGINT today)
        $sigint = \App\Models\AuditLog::where('module', 'SIGINT')
            ->whereDate('created_at', today())
            ->count();

        // weight: up to +40
        if ($sigint > 100) $score += 40;
        elseif ($sigint > 50) $score += 30;
        elseif ($sigint > 10) $score += 15;

        // 🔢 DELETE SPIKE (last 10 min)
        $deletes = \App\Models\AuditLog::where('action', 'DELETE')
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();

        // weight: up to +30
        if ($deletes >= 10) $score += 30;
        elseif ($deletes >= 5) $score += 20;

        // 🔢 MULTI-MODULE ACTIVITY (breadth)
        $modules = \App\Models\AuditLog::whereDate('created_at', today())
            ->distinct('module')
            ->count('module');

        // weight: up to +20
        if ($modules >= 4) $score += 20;
        elseif ($modules >= 2) $score += 10;

        // 🔢 RAPID ACTION RATE (last 5 min)
        $burst = \App\Models\AuditLog::where('created_at', '>=', now()->subMinutes(5))
            ->count();

        // weight: up to +10
        if ($burst >= 50) $score += 10;
        elseif ($burst >= 20) $score += 5;

        return min($score, 100);
    }
}
