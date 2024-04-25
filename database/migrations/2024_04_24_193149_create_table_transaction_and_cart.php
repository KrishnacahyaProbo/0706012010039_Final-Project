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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->bigInteger('total_price');
            $table->text('address');
            $table->double('longitude');
            $table->double('latitude');
            $table->double('distance_between');
            $table->integer('shipping_costs');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('vendor_id')->references('id')->on('users');
        });

        Schema::create('reasons', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->string('portion');
            $table->integer('quantity');
            $table->enum('status', ['customer_unpaid', 'customer_paid']);
            $table->unsignedBigInteger('refund_reason')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('menu_id')->references('id')->on('menu');
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('refund_reason')->references('id')->on('reasons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_transaction_and_cart');
    }
};
