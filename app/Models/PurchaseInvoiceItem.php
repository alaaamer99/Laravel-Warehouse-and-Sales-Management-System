<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_invoice_id',
        'product_id',
        'quantity_cartons',
        'quantity_units',
        'unit_price',
        'total_price',
    ];

    protected function casts(): array
    {
        return [
            'quantity_cartons' => 'integer',
            'quantity_units' => 'integer',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Get the purchase invoice that owns the item
     */
    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    /**
     * Get the product that owns the item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
