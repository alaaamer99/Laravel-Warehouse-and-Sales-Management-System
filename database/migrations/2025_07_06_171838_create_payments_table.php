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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->enum('payment_type', ['supplier_payment', 'customer_payment', 'representative_payment']);
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sales_representative_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('purchase_invoice_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sales_invoice_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم الذي سجل الدفعة
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check'])->default('cash');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
