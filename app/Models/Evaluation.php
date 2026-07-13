<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $primaryKey = 'evaluation_id';

    protected $fillable = [

        'user_id',

        'evaluation_month',

        'evaluation_year',

        'evaluation_status',

        'submitted_at',

        'work_performance_score',

        'attendance_score',

        'behavior_score',

        'total_score'

    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','user_id');
    }

    public function workPerformance()
    {
        return $this->hasMany(EvaluationWorkPerformance::class,'evaluation_id','evaluation_id');
    }

    public function attendance()
    {
        return $this->hasOne(EvaluationAttendance::class,'evaluation_id','evaluation_id');
    }

    public function behaviors()
    {
        return $this->hasMany(EvaluationBehavior::class,'evaluation_id','evaluation_id');
    }
}
