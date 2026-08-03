<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\EvaluationWorkPerformance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EvaluationService
{
    public function store(array $data): Evaluation
    {
        return DB::transaction(function () use ($data) {

            // Step 1
            // Create Evaluation
            $evaluation = Evaluation::create([
                'user_id' => Auth::id(),
                'evaluation_month' => $data['evaluation_month'],
                'evaluation_year' => $data['evaluation_year'],
                'evaluation_status' => 'submitted',
                'submitted_at' => now(),
                'created_by' => Auth::id(),

            ]);
            // Log::info($evaluation->toArray());

            // Step 2
            // Store Work Performance
            $this->storeWorkPerformance(
                $evaluation,
                $data['performances']
            );

            // Step 3
            // Attendance (Next)

            // Step 4
            // Behavior (Next)

            return $evaluation;

        });
    }

    /**
     * Store Work Performance
     */
    private function storeWorkPerformance(
        Evaluation $evaluation,
        array $performances
    ): void {

        $totalScore = 0;

        foreach ($performances as $performance) {

            $score = $this->calculateRowScore(
                $performance['achievement_percent']
            );

            EvaluationWorkPerformance::create([

                'evaluation_id' => $evaluation->evaluation_id,
                'activity' => $performance['activity'],
                'indicator' => $performance['indicator'],
                'achievement_percent' => $performance['achievement_percent'],
                'score' => $score,

            ]);

            $totalScore += $score;

        }

        $evaluation->update([

            'work_performance_score' => $this->calculateWorkPerformanceScore(
                $totalScore
            )

        ]);

    }

    /**
     * Calculate Row Score
     */
    private function calculateRowScore(
        float $achievementPercent
    ): float {

        return round(
            ($achievementPercent * 20) / 100,
            2
        );

    }

    /**
     * Calculate Work Performance Score
     */
    private function calculateWorkPerformanceScore(
        float $totalScore
    ): float {

        if ($totalScore > 0 && $totalScore <= 60) {

            return 0;

        }

        if ($totalScore > 60 && $totalScore <= 70) {

            return 15;

        }

        if ($totalScore > 70 && $totalScore <= 80) {

            return 30;

        }

        if ($totalScore > 80 && $totalScore <= 90) {

            return 45;

        }

        if ($totalScore > 90 && $totalScore <= 100) {

            return 60;

        }

        return 0;

    }
}