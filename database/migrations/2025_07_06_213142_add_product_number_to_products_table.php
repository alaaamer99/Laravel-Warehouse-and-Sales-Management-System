<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_number')->nullable()->after('name');
        });
        
        // Update existing products with auto-generated product numbers
        DB::statement("UPDATE products SET product_number = CONCAT('PRD-', LPAD(id, 6, '0')) WHERE product_number IS NULL");
        
        // Make the column unique after updating
        Schema::table('products', function (Blueprint $table) {
            $table->unique('product_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_number');
        });
    }
};
