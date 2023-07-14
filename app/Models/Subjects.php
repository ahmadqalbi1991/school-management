<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subjects extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug'];

    public function strands(): HasMany
    {
        return $this->hasMany(Strand::class, 'subject_id', 'id');
    }

    public function terms(): HasMany
    {
        return $this->hasMany(TermSubject::class, 'subject_id', 'id');
    }

    public function classes(): HasMany
    {
        return $this->hasMany(ClassSubject::class, 'subject_id', 'id');
    }

    public function school_class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id', 'id');
    }
}
