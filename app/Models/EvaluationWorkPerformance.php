<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationWorkPerformance extends Model
{
    use HasFactory;

    protected $table = 'evaluation_work_performance';

    protected $primaryKey = 'work_performance_id';

    protected $fillable = [
        'evaluation_id',
        'activity',
        'indicator',
        'achievement_percent',
        'score',
    ];

    protected $casts = [
        'achievement_percent' => 'decimal:2',
        'score' => 'decimal:2',
    ];

    public function evaluation()
    {
        return $this->belongsTo(
            Evaluation::class,
            'evaluation_id',
            'evaluation_id'
        );
    }
}