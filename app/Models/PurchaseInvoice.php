<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'user_id',
        'invoice_number',
        'invoice_date',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'payment_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'total_amount' => 'decimal:2',
        ];
    }

    /**
     * Get the supplier that owns the invoice
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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
        return $this->hasMany(PurchaseInvoiceItem::class);
    }
}
