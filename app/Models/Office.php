<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'office_id';

    protected $fillable = [
        'department_id',
        'office_name_kh',
        'office_name_en',
        'office_code',
        'desc',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id',
            'department_id'
        );
    }
    public function users()
    {
        return $this->hasMany(
            User::class,
            'office_id',
            'office_id'
        );
    }
}
