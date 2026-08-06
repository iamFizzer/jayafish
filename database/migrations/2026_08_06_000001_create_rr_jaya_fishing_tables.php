<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->enum('role', ['admin', 'karyawan', 'owner'])->default('karyawan')->after('password');
            $table->boolean('is_active')->default(true)->after('role');
        });
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->enum('type', ['alat_pancing', 'pakan_ikan']); $table->string('color', 20)->default('#0f766e'); $table->timestamps();
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id(); $table->foreignId('category_id')->constrained(); $table->string('name'); $table->string('sku')->unique(); $table->decimal('price', 14, 2); $table->string('unit', 30); $table->integer('stock')->default(0); $table->integer('minimum_stock')->default(5); $table->string('image')->nullable(); $table->timestamps();
        });
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id(); $table->foreignId('product_id')->constrained(); $table->foreignId('user_id')->constrained(); $table->integer('quantity'); $table->decimal('purchase_price', 14, 2)->nullable(); $table->date('received_at'); $table->text('notes')->nullable(); $table->timestamps();
        });
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); $table->string('invoice_number')->unique(); $table->foreignId('user_id')->constrained(); $table->string('customer_name')->nullable(); $table->date('transaction_date'); $table->decimal('total', 14, 2); $table->text('notes')->nullable(); $table->timestamps();
        });
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id(); $table->foreignId('transaction_id')->constrained()->cascadeOnDelete(); $table->foreignId('product_id')->constrained(); $table->integer('quantity'); $table->decimal('price', 14, 2); $table->decimal('subtotal', 14, 2); $table->timestamps();
        });
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $table->string('action'); $table->text('description'); $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs'); Schema::dropIfExists('transaction_items'); Schema::dropIfExists('transactions'); Schema::dropIfExists('stock_ins'); Schema::dropIfExists('products'); Schema::dropIfExists('categories');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['username', 'role', 'is_active']));
    }
};
