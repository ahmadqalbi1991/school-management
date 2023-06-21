<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Substrand extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'strand_id'];

    public function strand(): BelongsTo
    {
        return $this->belongsTo(Strand::class, 'strand_id', 'id');
    }

        public function learning_activities(): HasMany
        {
            return $this->hasMany(LearningActivity::class, 'sub_strand_id');
        }
}
