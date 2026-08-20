<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $primaryKey = 'evaluation_id';

    protected $fillable = [

        'evaluation_period_id',
        'evaluator_id',
        'evaluatee_id',
        'evaluation_status',
        'submitted_at',
        'created_by',
        'updated_by'

    ];

    public function evaluator()
    {
        return $this->belongsTo(
            User::class,
            'evaluator_id',
            'user_id'
        );
    }

    public function evaluatee()
    {
        return $this->belongsTo(
            User::class,
            'evaluatee_id',
            'user_id'
        );
    }

    public function workPerformance()
    {
        return $this->hasMany(EvaluationWorkPerformance::class, 'evaluation_id', 'evaluation_id');
    }

    public function attendance()
    {
        return $this->hasOne(EvaluationAttendance::class, 'evaluation_id', 'evaluation_id');
    }

    public function behavior()
    {
        return $this->hasOne(
            EvaluationBehavior::class,
            'evaluation_id',
            'evaluation_id'
        );
    }

    public function getRouteKeyName(): string
    {
        return 'evaluation_id';
    }
}
