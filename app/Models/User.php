<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi: Teknisi bisa punya banyak servis yang ditugaskan
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'technician_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isTeknisi(): bool
    {
        return $this->role === 'Teknisi';
    }
}