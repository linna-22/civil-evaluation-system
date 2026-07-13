<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationBehavior extends Model
{
    use HasFactory;

    protected $primaryKey = 'behavior_id';

    protected $fillable = [
        'evaluation_id',
        'criteria_name',
        'score',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
