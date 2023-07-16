<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tsc_number',
        'phone_number',
        'role',
        'admission_number',
        'parent_name',
        'parent_email',
        'parent_phone_number',
        'stream_id',
        'upi_number',
        'admission_date',
        'school_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }


    public function get_roles()
    {
        $roles = [];
        foreach ($this->getRoleNames() as $key => $role) {
            $roles[$key] = $role;
        }

        return $roles;
    }

    public function school_admins(): HasMany
    {
        return $this->hasMany(SchoolAdmins::class, 'admin_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class,'school_id', 'id');
    }

    public function learners(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(StudentAssessment::class, 'learner_id', 'id');
    }

    public function summative_assessments(): HasMany
    {
        return $this->hasMany(SummativeAssessment::class, 'learner_id', 'id');
    }

    public function stream(): BelongsTo
    {
        return $this->belongsTo(Stream::class, 'stream_id', 'id');
    }
}
