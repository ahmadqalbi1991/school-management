<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolAdmins extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['school_id', 'admin_id'];
}
