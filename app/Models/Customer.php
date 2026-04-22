<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'price_type',
        'balance',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the sales invoices for this customer
     */
    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class);
    }

    /**
     * Get the payments for this customer
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Calculate total purchases amount
     */
    public function getTotalPurchasesAttribute()
    {
        return $this->salesInvoices()->sum('total_amount');
    }

    /**
     * Calculate total paid amount
     */
    public function getTotalPaidAttribute()
    {
        return $this->salesInvoices()->sum('paid_amount');
    }

    /**
     * Calculate remaining balance
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->getTotalPurchasesAttribute() - $this->getTotalPaidAttribute();
    }
}
