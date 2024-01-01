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
        Schema::create('register_want_estate_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('register_estate_id')->references('id')->on('register_estates');
            $table->foreignId('register_want_estate_type_id')->references('id')->on('register_want_estate_types');
            $table->foreignId('estate_field_id')->references('id')->on('estate_fields');
            $table->string('from_text')->nullable();
            $table->string('to_text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_want_estate_fields');
    }
};
