<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'country',
        'province',
        'city',
        'district',
        'postal_code',
        'role',
        'photo',
        'is_livestreaming',
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
            'name' => 'string',
            'email' => 'string',
            'phone' => 'string',
            'address' => 'string',
            'country' => 'string',
            'province' => 'string',
            'city' => 'string',
            'district' => 'string',
            'postal_code' => 'string',
            'role' => 'string',
            'photo' => 'string',
            'is_livestreaming' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship with Product
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    // Relationship with Category
    public function categories()
    {
        return $this->hasMany(Category::class, 'seller_id');
    }
}
