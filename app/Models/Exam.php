<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'term_id', 'school_id'];

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class, 'term_id');
    }
}
