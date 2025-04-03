<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relación con órdenes
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relación con carrito
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    // Verificar si es admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
