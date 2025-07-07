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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->foreignUuid('category_id')->constrained();
            $table->enum('status', ['AVAILABLE', 'RENTED', 'RESERVED', 'DAMAGED', 'UNAVAILABLE', 'UNDER_MAINTENANCE'])->default('AVAILABLE');
            $table->enum('transmission', ['MANUAL', 'AUTOMATIC'])->default('MANUAL');
            $table->string('plate_number')->unique();
            $table->string('fuel_type');
            $table->string('color');
            $table->integer('rate_per_day');
            $table->integer('rate_per_hour');
            $table->integer('capacity');
            $table->integer('mileage');
            $table->string('model');
            $table->string('brand');
            $table->string('type');            
            $table->integer('year');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
