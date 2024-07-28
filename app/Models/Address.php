<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'country',
        'province',
        'city',
        'district',
        'postal_code',
        'address',
        'is_default',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'country' => 'string',
            'province' => 'string',
            'city' => 'string',
            'district' => 'string',
            'postal_code' => 'string',
            'address' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'is_default' => 'boolean',
        ];
    }
}
