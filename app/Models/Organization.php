<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Organization extends Model
{
    use HasFactory;

    protected $primaryKey = 'organization_id';

    protected $fillable = [
        'organization_code',
        'organization_name',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function departments()
    {
        return $this->hasMany(Department::class, 'organization_id', 'organization_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'organization_id', 'organization_id');
    }
}