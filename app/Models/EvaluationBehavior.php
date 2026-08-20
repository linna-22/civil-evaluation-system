<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationBehavior extends Model
{
    use HasFactory;

    protected $table = 'evaluation_behavior';

    protected $primaryKey = 'behavior_id';

    protected $fillable = [
        'evaluation_id',

        // ១. ឥរិយាបថ និងវិន័យ
        'discipline',
        'responsibility',
        'professional_ethics',

        // ២. សមត្ថភាពវិជ្ជាជីវៈ
        'work_performance',
        'self_development',
        'initiative_creativity',

        // ៣. ភាពជាអ្នកដឹកនាំ
        'teamwork',
        'interpersonal_skill',
        'work_under_pressure',
        'leadership',

        // Total
        'total_score',
    ];

    protected $casts = [
        'total_score' => 'decimal:2',
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