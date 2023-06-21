<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceLevel extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'detail', 'points', 'created_by'];
}
