<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasFactory;
    protected $fillable = [
        'school_name',
        'address',
        'phone_number',
        'email',
        'logo',
        'active',
        'school_website',
        'school_moto',
        'slug'
    ];

    public function admins(): HasMany
    {
        return $this->hasMany(SchoolAdmins::class);
    }
}
