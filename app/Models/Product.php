<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_number',
        'supplier_id',
        'units_per_carton',
        'stock_cartons',
        'stock_units',
        'purchase_price',
        'wholesale_price',
        'retail_price',
        'description',
        'barcode',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'units_per_carton' => 'integer',
            'stock_cartons' => 'integer',
            'stock_units' => 'integer',
            'purchase_price' => 'decimal:2',
            'wholesale_price' => 'decimal:2',
            'retail_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the supplier that owns the product
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the purchase invoice items
     */
    public function purchaseInvoiceItems()
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }

    /**
     * Get the sales invoice items
     */
    public function salesInvoiceItems()
    {
        return $this->hasMany(SalesInvoiceItem::class);
    }

    /**
     * Get the representative withdrawals
     */
    public function representativeWithdrawals()
    {
        return $this->hasMany(RepresentativeWithdrawal::class);
    }

    /**
     * Calculate total stock in units
     */
    public function getTotalStockUnitsAttribute()
    {
        return ($this->stock_cartons * $this->units_per_carton) + $this->stock_units;
    }

    /**
     * Get the stock display format
     */
    public function getStockDisplayAttribute()
    {
        return "{$this->stock_cartons} كرتونة و {$this->stock_units} قطعة";
    }

    /**
     * Check if product is low in stock
     */
    public function isLowStock($threshold = 10)
    {
        return $this->getTotalStockUnitsAttribute() <= $threshold;
    }

    /**
     * Add stock to product
     */
    public function addStock($cartons, $units)
    {
        // Convert excess units to cartons
        $totalUnits = $this->stock_units + $units;
        $additionalCartons = intval($totalUnits / $this->units_per_carton);
        $remainingUnits = $totalUnits % $this->units_per_carton;

        $this->stock_cartons += $cartons + $additionalCartons;
        $this->stock_units = $remainingUnits;
        $this->save();
    }

    /**
     * Remove stock from product
     */
    public function removeStock($cartons, $units)
    {
        $totalRequestedUnits = ($cartons * $this->units_per_carton) + $units;
        $currentTotalUnits = $this->getTotalStockUnitsAttribute();

        if ($totalRequestedUnits > $currentTotalUnits) {
            throw new \Exception('المخزون المطلوب أكثر من المخزون المتاح');
        }

        $newTotalUnits = $currentTotalUnits - $totalRequestedUnits;
        $this->stock_cartons = intval($newTotalUnits / $this->units_per_carton);
        $this->stock_units = $newTotalUnits % $this->units_per_carton;
        $this->save();
    }
}
