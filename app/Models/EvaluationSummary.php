<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationSummary extends Model
{
    protected $table = 'evaluation_summaries';

    protected $primaryKey = 'evaluation_summary_id';

    protected $fillable = [
        'evaluation_period_user_id',
        'work_performance_score',
        'attendance_score',
        'behavior_score',
        'total_score',
        'calculated_at',
    ];

    /**
     * Evaluation period participant.
     */
    public function evaluationPeriodUser(): BelongsTo
    {
        return $this->belongsTo(
            EvaluationPeriodUser::class,
            'evaluation_period_user_id',
            'evaluation_period_user_id'
        );
    }
}