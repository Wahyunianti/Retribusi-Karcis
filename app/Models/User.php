<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Role;
use App\Models\Masuk;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $fillable = [
        'id',
        'nama',
        'username',
        'password',
        'nip',
        'no_telp',
        'status',
        'bagian',
        'foto',
        'role_id'
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

    public function roles(): BelongsTo {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function isAdministrator()
    {
        return $this->roles->role_name == 'administrator';
    }

    public function isAdmin()
    {
        return $this->roles->role_name == 'admin';
    }

    public function isKepala()
    {
        return $this->roles->role_name == 'kepala';
    }


}
