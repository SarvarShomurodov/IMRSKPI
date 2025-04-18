<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['firstName', 'lastName', 'email', 'phone', 'position', 'salary', 'project_id', 'lastDate','password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            if (is_null($model->lastDate)) {
                $model->lastDate = now()->toDateString();  // Bugungi sana
            }
        });
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class, 'user_id');
    }
    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }
}
