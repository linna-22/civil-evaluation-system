<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use App\Models\EvaluationPeriodUser;
use App\Models\EvaluationSummary;
use Illuminate\Support\Facades\DB;

class EvaluationSummaryService
{
    /**
     * Calculate all evaluation summaries
     * for one evaluation period.
     */
    public function calculate(
        EvaluationPeriod $evaluationPeriod
    ): void {

        DB::transaction(function () use ($evaluationPeriod) {

            /*
            |--------------------------------------------------------------------------
            | Get All Participants
            |--------------------------------------------------------------------------
            */

            $periodUsers = EvaluationPeriodUser::query()
                ->where(
                    'evaluation_period_id',
                    $evaluationPeriod->evaluation_period_id
                )
                ->get();


            /*
            |--------------------------------------------------------------------------
            | Calculate Each Employee
            |--------------------------------------------------------------------------
            */

            foreach ($periodUsers as $periodUser) {

                $evaluateeId = $periodUser->user_id;


                /*
                |--------------------------------------------------------------------------
                | 1. Work Performance /60
                |--------------------------------------------------------------------------
                */

                $workPerformanceScore =
                    $this->calculateWorkPerformance(
                        $evaluationPeriod,
                        $evaluateeId
                    );


                /*
                |--------------------------------------------------------------------------
                | 2. Attendance /20
                |--------------------------------------------------------------------------
                */

                $attendanceScore =
                    $this->calculateAttendance(
                        $evaluationPeriod,
                        $evaluateeId
                    );


                /*
                |--------------------------------------------------------------------------
                | 3. Behavior /20
                |--------------------------------------------------------------------------
                |
                | Behavior is peer-to-peer.
                | Therefore we calculate the average of all
                | submitted behavior evaluations for this employee.
                |
                */

                $behaviorScore =
                    $this->calculateBehavior(
                        $evaluationPeriod,
                        $evaluateeId
                    );


                /*
                |--------------------------------------------------------------------------
                | Final Score /100
                |--------------------------------------------------------------------------
                */

                $totalScore =
                    $workPerformanceScore
                    + $attendanceScore
                    + $behaviorScore;


                /*
                |--------------------------------------------------------------------------
                | Save Summary
                |--------------------------------------------------------------------------
                */

                EvaluationSummary::updateOrCreate(
                    [
                        'evaluation_period_user_id' =>
                            $periodUser->evaluation_period_user_id,
                    ],
                    [
                        'work_performance_score' =>
                            $workPerformanceScore,

                        'attendance_score' =>
                            $attendanceScore,

                        'behavior_score' =>
                            $behaviorScore,

                        'total_score' =>
                            round($totalScore, 2),

                        'calculated_at' =>
                            now(),
                    ]
                );
            }
        });
    }


    /**
     * Calculate Work Performance /60.
     */
    private function calculateWorkPerformance(EvaluationPeriod $evaluationPeriod, int $evaluateeId): float 
    {
        $evaluation = Evaluation::query()
            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->where(
                'evaluatee_id',
                $evaluateeId
            )
            ->where(
                'evaluation_type',
                'work_performance'
            )
            ->where(
                'evaluation_status',
                'submitted'
            )
            ->with('workPerformance')
            ->first();

        if (!$evaluation) {
            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Sum Activity Scores
        |--------------------------------------------------------------------------
        |
        | Activity scores represent the employee's
        | achievement result out of 100.
        |
        */

        $totalAchievementScore = $evaluation
            ->workPerformance
            ->sum('score');

        /*
        |--------------------------------------------------------------------------
        | Keep between 0 - 100
        |--------------------------------------------------------------------------
        */

        $totalAchievementScore = min(
            100,
            max(0, $totalAchievementScore)
        );

        /*
        |--------------------------------------------------------------------------
        | Convert Achievement /100 to Work Performance /60
        |--------------------------------------------------------------------------
        */

        if ($totalAchievementScore <= 60) {
            return 0;
        }

        if ($totalAchievementScore <= 70) {
            return 15;
        }

        if ($totalAchievementScore <= 80) {
            return 30;
        }

        if ($totalAchievementScore <= 90) {
            return 45;
        }

        return 60;
    }

    /**
     * Calculate Attendance /20.
     */
    private function calculateAttendance(
        EvaluationPeriod $evaluationPeriod,
        int $evaluateeId
    ): float {

        $evaluation = Evaluation::query()
            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->where(
                'evaluatee_id',
                $evaluateeId
            )
            ->where(
                'evaluation_type',
                'attendance'
            )
            ->where(
                'evaluation_status',
                'submitted'
            )
            ->with('attendance')
            ->first();


        if (
            !$evaluation ||
            !$evaluation->attendance
        ) {
            return 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Attendance already stores the final /20 score.
        |--------------------------------------------------------------------------
        */

        $score =
            $evaluation->attendance->attendance_score;


        /*
        |--------------------------------------------------------------------------
        | Maximum Attendance Score = 20
        |--------------------------------------------------------------------------
        */

        return round(
            min(20, max(0, $score)),
            2
        );
    }


    /**
     * Calculate Behavior /20.
     *
     * Behavior is peer-to-peer.
     */
    private function calculateBehavior(
        EvaluationPeriod $evaluationPeriod,
        int $evaluateeId
    ): float {

        $evaluations = Evaluation::query()
            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->where(
                'evaluatee_id',
                $evaluateeId
            )
            ->where(
                'evaluation_type',
                'behavior'
            )
            ->where(
                'evaluation_status',
                'submitted'
            )
            ->with('behavior')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | No Peer Evaluations
        |--------------------------------------------------------------------------
        */

        if ($evaluations->isEmpty()) {
            return 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Get Peer Scores
        |--------------------------------------------------------------------------
        */

        $scores = $evaluations
            ->filter(
                fn($evaluation) =>
                $evaluation->behavior !== null
            )
            ->map(
                fn($evaluation) =>
                (float) $evaluation
                    ->behavior
                    ->total_score
            );


        if ($scores->isEmpty()) {
            return 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Peer Average
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | Peer 1 = 16
        | Peer 2 = 18
        | Peer 3 = 17
        |
        | Average = 17
        |
        */

        $average = $scores->average();


        /*
        |--------------------------------------------------------------------------
        | Maximum Behavior Score = 20
        |--------------------------------------------------------------------------
        */

        return round(
            min(20, max(0, $average)),
            2
        );
    }
}