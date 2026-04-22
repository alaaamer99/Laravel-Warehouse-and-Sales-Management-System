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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('شركة بهجة للمنظفات');
            $table->string('company_address')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('company_favicon')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('commercial_register')->nullable();
            $table->text('invoice_terms')->nullable();
            $table->string('currency', 10)->default('ج.م');
            $table->string('timezone')->default('Africa/Cairo');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
