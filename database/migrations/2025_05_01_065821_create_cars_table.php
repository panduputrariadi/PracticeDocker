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
        Schema::create('cars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->enum('status', ['AVAILABLE', 'RENTED', 'RESERVED', 'DAMAGED', 'UNAVAILABLE', 'UNDER_MAINTENANCE'])->default('AVAILABLE');
            $table->integer('price');
            $table->string('color');
            $table->string('type');
            $table->text('description');
            $table->string('plateNumber')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
