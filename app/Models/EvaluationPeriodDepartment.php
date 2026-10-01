<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationPeriodDepartment extends Model
{
    protected $table = 'evaluation_period_departments';

    protected $primaryKey = 'evaluation_period_department_id';

    protected $casts = [
        'finalized_at' => 'datetime',
    ];

    protected $fillable = [
        'evaluation_period_id',
        'department_id',
        'review_status',
        'finalized_by',
        'finalized_at',
    ];

    /**
     * Evaluation period.
     */
    public function evaluationPeriod(): BelongsTo
    {
        return $this->belongsTo(
            EvaluationPeriod::class,
            'evaluation_period_id',
            'evaluation_period_id'
        );
    }

    /**
     * User who finalized this department's evaluation results.
     */
    public function finalizer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'finalized_by',
            'user_id'
        );
    }

    public function isFinalized(): bool
    {
        return $this->review_status === 'finalized';
    }

    /**
     * Department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class,
            'department_id',
            'department_id'
        );
    }
}
