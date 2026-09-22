<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationPeriodDepartment extends Model
{
    protected $table = 'evaluation_period_departments';

    protected $primaryKey = 'evaluation_period_department_id';

    protected $fillable = [
        'evaluation_period_id',
        'department_id',
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