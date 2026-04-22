<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
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
     * Get the products for this supplier
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the purchase invoices for this supplier
     */
    public function purchaseInvoices()
    {
        return $this->hasMany(PurchaseInvoice::class);
    }

    /**
     * Get the payments for this supplier
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
        return $this->purchaseInvoices()->sum('total_amount');
    }

    /**
     * Calculate total paid amount
     */
    public function getTotalPaidAttribute()
    {
        return $this->purchaseInvoices()->sum('paid_amount');
    }

    /**
     * Calculate remaining balance
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->getTotalPurchasesAttribute() - $this->getTotalPaidAttribute();
    }
}
