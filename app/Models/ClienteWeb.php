<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ClienteWeb extends Authenticatable
{
    use Notifiable;

    protected $table = 'clientes_web';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
