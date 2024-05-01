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
        Schema::create('testimonies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('customer_id');
            $table->integer('rating');
            $table->text('description');
            $table->string('testimony_photo')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('vendor_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonies');
    }
};
