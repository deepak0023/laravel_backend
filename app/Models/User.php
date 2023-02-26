<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_rl_id'
    ];

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
    ];

    public function todo() {
        return $this->hasMany(Todo::class, 'td_user_id', 'id');
    }

    public function article() {
        return $this->hasMany(Article::class, 'ar_user_id', 'id');
    }

    public function course() {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id');
    }

    public function roles() {
        return $this->belongsTo(Roles::class, 'user_rl_id', 'rl_id');
    }
}
