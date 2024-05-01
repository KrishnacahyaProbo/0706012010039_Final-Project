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
        Schema::create('reasons', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->bigInteger('subtotal');
            $table->text('address');
            $table->double('longitude');
            $table->double('latitude');
            $table->double('distance_between');
            $table->integer('shipping_costs');
            $table->enum('status', ['customer_unpaid', 'customer_paid', 'customer_canceled', 'vendor_packing', 'vendor_delivering', 'customer_received', 'customer_problem']);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users');
        });

        Schema::create('transactions_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('menu_id');
            $table->text('note')->nullable();
            $table->date('schedule_date');
            $table->string('portion');
            $table->integer('quantity');
            $table->bigInteger('price');
            $table->bigInteger('total_price');
            $table->unsignedBigInteger('refund_reason')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('vendor_id')->references('id')->on('users');
            $table->foreign('menu_id')->references('id')->on('menu');
            $table->foreign('refund_reason')->references('id')->on('reasons');
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('menu_id');
            $table->date('schedule_date');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->string('portion');
            $table->integer('quantity');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('menu_id')->references('id')->on('menu');
            $table->foreign('transaction_id')->references('id')->on('transactions');
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
