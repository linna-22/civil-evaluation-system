<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationPeriod extends Model
{
    protected $table = 'evaluation_periods';

    protected $primaryKey = 'evaluation_period_id';

    protected $fillable = [
        'name_kh',
        'name_en',
        'month',
        'year',
        'close_type',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'closed_by',
        'open_at',
        'close_at',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'open_at' => 'datetime',
        'close_at' => 'datetime',
    ];

    /**
     * User who created the evaluation period.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'user_id'
        );
    }

    /**
     * User who closed the evaluation period.
     */
    public function closer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'closed_by',
            'user_id'
        );
    }

    /**
     * Users assigned to this evaluation period.
     */
    public function periodUsers(): HasMany
    {
        return $this->hasMany(
            EvaluationPeriodUser::class,
            'evaluation_period_id',
            'evaluation_period_id'
        );
    }
}