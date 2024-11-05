<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Kebun;
use App\Models\Produk;
use App\Models\Rating;
use App\Models\Transaksi;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'role',
        'username',
        'email_verified_at',
        'password',
        'email',
        'firstname',
        'lastname'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function kebun(): HasMany
    {
        return $this->hasMany(Kebun::class, 'id_user', 'user_id');
    }
    public function rating(): HasMany
    {
        return $this->hasMany(Rating::class, 'id_user', 'user_id');
    }
    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'id_user', 'user_id');
    }
    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'id_user', 'user_id');
    }

}
