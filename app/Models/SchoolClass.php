<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use HasFactory;
    protected $fillable = ['class', 'slug'];

    public $timestamps = false;

    public function class_subjects(): HasMany
    {
        return $this->hasMany(ClassSubject::class, 'class_id', 'id');
    }
}
