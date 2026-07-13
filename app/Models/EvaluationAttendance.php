<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationAttendance extends Model
{
    use HasFactory;

    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'evaluation_id',
        'permission_days',
        'absent_days',
        'late_hours',
        'attendance_percent',
        'attendance_score',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
