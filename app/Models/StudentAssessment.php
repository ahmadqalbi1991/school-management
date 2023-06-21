<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAssessment extends Model
{
    use HasFactory;

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subjects::class, 'subject_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'learner_id', 'id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(PerformanceLevel::class, 'performance_level_id', 'id');
    }
}
