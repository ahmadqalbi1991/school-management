<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummativePerformnceLevel extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'detail', 'min_point', 'max_point', 'created_by'];
}
