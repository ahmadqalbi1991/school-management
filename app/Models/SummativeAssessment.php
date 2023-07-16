<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SummativeAssessment extends Model
{
    use HasFactory;
    protected $fillable = [
        'class_id',
        'subject_id',
        'stream_id',
        'term_id',
        'exam_id',
        'learner_id',
        'performance_level_id',
        'points'
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(SummativePerformnceLevel::class, 'performance_level_id', 'id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subjects::class, 'subject_id', 'id');
    }
}
