<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gitlab_id',
        'gitlab_username',
        'gitlab_data',
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
        'password' => 'hashed',
        'gitlab_data' => 'json',
    ];

    public function issuesAssigned(): HasMany
    {
        return $this->hasMany(Issue::class, 'assigned_to', 'gitlab_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(User::class, 'gitlab_id');
    }

    public function time_entries(): HasMany
    {
        return $this->hasMany(TimeEntry::class)
            ->orderByDesc('ended_at');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'owner_id');
    }
}
