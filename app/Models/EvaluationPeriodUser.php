<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationPeriodUser extends Model
{
    protected $table = 'evaluation_period_users';

    protected $primaryKey = 'evaluation_period_user_id';

    protected $fillable = [
        'evaluation_period_id',
        'user_id',
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
     * Participant.
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