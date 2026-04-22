<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'sales_representative_id',
        'user_id',
        'invoice_number',
        'invoice_date',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'payment_status',
        'payment_type',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
        ];
    }

    /**
     * Get the customer that owns the invoice
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the sales representative that owns the invoice
     */
    public function salesRepresentative()
    {
        return $this->belongsTo(SalesRepresentative::class);
    }

    /**
     * Get the user that created the invoice
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the invoice items
     */
    public function items()
    {
        return $this->hasMany(SalesInvoiceItem::class);
    }

    /**
     * Get the payments for this invoice
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
