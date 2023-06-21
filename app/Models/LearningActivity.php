<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningActivity extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'sub_strand_id'];

    public function sub_strand(): BelongsTo
    {
        return $this->belongsTo(Substrand::class, 'sub_strand_id', 'id');
    }
}
