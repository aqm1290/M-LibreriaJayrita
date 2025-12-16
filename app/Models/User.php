<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol', // ← nuestro campo simple
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
            'rol' => 'string',
        ];
    }

    // MÉTODOS DE AYUDA PARA ROLES (los usamos en el menú y middleware)
    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function esCajero(): bool
    {
        return $this->rol === 'cajero';
    }

    public function esVendedor(): bool
    {
        return $this->rol === 'vendedor';
    }
    
}