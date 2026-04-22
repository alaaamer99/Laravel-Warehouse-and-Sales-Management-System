<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'payment_type',
        'supplier_id',
        'customer_id', 
        'sales_representative_id',
        'purchase_invoice_id',
        'sales_invoice_id',
        'user_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    /**
     * Get the sales invoice that owns the payment
     */
    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    /**
     * Get the purchase invoice that owns the payment
     */
    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    /**
     * Get the customer that owns the payment
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the supplier that owns the payment
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the sales representative that owns the payment
     */
    public function salesRepresentative()
    {
        return $this->belongsTo(SalesRepresentative::class);
    }

    /**
     * Get the user that created the payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
