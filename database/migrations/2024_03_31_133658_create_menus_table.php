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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->index(); // Use unsignedBigInteger for the foreign key
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('menu_name')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('type', ['spicy', 'no_spicy'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
