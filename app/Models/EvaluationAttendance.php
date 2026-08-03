<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationAttendance extends Model
{
    use HasFactory;

    protected $table = 'evaluation_attendance';
    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'evaluation_id',
        'approved_leave_count',
        'unapproved_leave_count',
        'late_hours',
        'leave_early_hours',
        'attendance_percent',
        'attendance_score',
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
