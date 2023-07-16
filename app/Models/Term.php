<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Term extends Model
{
    use HasFactory;
    protected $fillable = ['year', 'term', 'start_date', 'end_date', 'school_id'];

    public function subjects(): HasMany
    {
        return $this->hasMany(TermSubject::class, 'term_id', 'id');
    }
}
