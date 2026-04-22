<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->integer('units_per_carton'); // عدد وحدات المنتج داخل الكرتونة الواحدة
            $table->integer('stock_cartons')->default(0); // العدد بالكرتونة
            $table->integer('stock_units')->default(0); // العدد بالوحدة
            $table->decimal('purchase_price', 10, 2); // سعر جملة الشراء
            $table->decimal('wholesale_price', 10, 2); // سعر البيع بالجملة
            $table->decimal('retail_price', 10, 2); // سعر البيع بالتجزئة
            $table->text('description')->nullable();
            $table->string('barcode')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
