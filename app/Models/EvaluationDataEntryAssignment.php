<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationDataEntryAssignment extends Model
{
    protected $table = 'evaluation_data_entry_assignments';

    protected $primaryKey = 'evaluation_data_entry_assignment_id';

    protected $fillable = [
        'evaluation_period_id',
        'department_id',
        'office_id',
        'user_id',
        'scope',
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

    /**
     * Office.
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(
            Office::class,
            'office_id',
            'office_id'
        );
    }

    /**
     * Data-entry user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'user_id'
        );
    }
}