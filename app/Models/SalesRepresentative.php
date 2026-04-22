<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesRepresentative extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
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
     * Get the user that owns the sales representative
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sales invoices for this representative
     */
    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class);
    }

    /**
     * Get the withdrawals for this representative
     */
    public function withdrawals()
    {
        return $this->hasMany(RepresentativeWithdrawal::class);
    }

    /**
     * Get the payments for this representative
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Calculate total sales amount
     */
    public function getTotalSalesAttribute()
    {
        return $this->salesInvoices()->sum('total_amount');
    }

    /**
     * Calculate total collected amount
     */
    public function getTotalCollectedAttribute()
    {
        return $this->salesInvoices()->sum('paid_amount');
    }

    /**
     * Calculate total withdrawals value
     */
    public function getTotalWithdrawalsAttribute()
    {
        return $this->withdrawals()->sum('total_value');
    }

    /**
     * Calculate outstanding balance
     */
    public function getOutstandingBalanceAttribute()
    {
        return $this->getTotalWithdrawalsAttribute() - $this->getTotalCollectedAttribute();
    }
}
