<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'address_id',
        'seller_id',
        'total_price',
        'shipping_price',
        'grand_total',
        'status',
        'payment_va_name',
        'payment_va_number',
        'shipping_service',
        'shipping_receipt_number',
        'transaction_number',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'customer_id' => 'integer',
            'address_id' => 'integer',
            'seller_id' => 'integer',
            'total_price' => 'decimal:2',
            'shipping_price' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'status' => 'string',
            'payment_va_name' => 'string',
            'payment_va_number' => 'string',
            'shipping_service' => 'string',
            'shipping_receipt_number' => 'string',
            'transaction_number' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Relationship with Customer
    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Address
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // Relationship with Seller
    public function seller()
    {
        return $this->belongsTo(User::class);
    }
}
