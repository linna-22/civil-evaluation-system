<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationWorkPerformance extends Model
{
    use HasFactory;

    protected $primaryKey = 'work_performance_id';

    protected $fillable = [
        'evaluation_id',
        'expected_result',
        'actual_result',
        'achievement_percent',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
