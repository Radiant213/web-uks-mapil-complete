<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User_UKS extends Authenticatable
{
    // Kasih tau Laravel kalo nama tabel kita ini
    protected $table = 'user__u_k_s';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // Password harus di-hash sama Laravel otomatis (fitur Laravel 10/11)
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
