<?php

namespace App\Services\IndicatorHistoryService;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class IndicatorHistoryService
{

    public function getSummedAlerts($alerts, $lt, $gt)
    {
        $startDate = Carbon::parse($lt);
        $endDate = Carbon::parse($gt);
        if ($alerts->isEmpty()) {
            return [];
        }
        $diffInDays = $startDate->diffInDays($endDate);
        Log::info('diffInDays: ' . $diffInDays);
        if ($diffInDays < 7) {
            if ($diffInDays == 0) {
                $label = Carbon::parse($startDate)->format('d M');
                $date = Carbon::parse($startDate)->format('Y-m-d');
            } else {
                $label = Carbon::parse($startDate)->format('d M') . ' - ' . Carbon::parse($endDate)->format('d M');
                $date = Carbon::parse($startDate)->format('Y-m-d') . ' - ' . Carbon::parse($endDate)->format('Y-m-d');
            }
            $summedAlerts = [
                [
                    'label' => $label,
                    'date' =>  $date,
                    'sum_area' => $alerts->sum('area'),
                    'sum_intensity' => $alerts->sum('intensity'),
                ],
            ];
        } elseif ($diffInDays < 30) {
            $weeksGrouped = $alerts->groupBy(function ($alert) {
                return Carbon::parse($alert['alert_datetime'])->startOfWeek()->format('Y-m-d');
            });
            $summedAlerts = [];
            foreach ($weeksGrouped as $date => $alerts) {
                $sumArea = $alerts->sum('area');
                $sumIntensity = $alerts->sum('intensity');
                $weekStart = Carbon::parse($date)->startOfWeek()->format('d M');
                $weekEnd = Carbon::parse($date)->endOfWeek()->format('d M');
                $label = $weekStart . ' - ' . $weekEnd;
                $summedAlerts[] = [
                    'label' => $label,
                    'date' => $date,
                    'sum_area' => $sumArea,
                    'sum_intensity' => $sumIntensity,
                ];
            }
        } else {
            $alertsGrouped = $alerts->groupBy(function ($alert) {
                return Carbon::parse($alert['alert_datetime'])->format('Y-m');
            });

            $summedAlerts = [];
            foreach ($alertsGrouped as $date => $alerts) {
                $sumArea = $alerts->sum('area');
                $sumIntensity = $alerts->sum('intensity');
                $monthLabel = Carbon::parse($date)->format('M');
                $summedAlerts[] = [
                    'label' => $monthLabel,
                    'date' => Carbon::parse($date)->format('Y-m'),
                    'sum_area' => $sumArea,
                    'sum_intensity' => $sumIntensity,
                ];
            }
        }

        return $summedAlerts;
    }
}
