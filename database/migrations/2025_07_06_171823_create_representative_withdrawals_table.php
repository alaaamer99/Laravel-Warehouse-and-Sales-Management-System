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
        Schema::create('representative_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->string('withdrawal_number')->unique();
            $table->foreignId('sales_representative_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم الذي أنشأ العملية
            $table->date('withdrawal_date');
            $table->integer('quantity_cartons');
            $table->integer('quantity_units');
            $table->decimal('unit_price', 10, 2); // سعر التكلفة للمندوب
            $table->decimal('total_value', 15, 2);
            $table->enum('status', ['pending', 'delivered', 'returned'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('representative_withdrawals');
    }
};
