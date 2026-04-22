<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_invoice_id',
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
     * Get the sales invoice that owns the item
     */
    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    /**
     * Get the product that owns the item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
