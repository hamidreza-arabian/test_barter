<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * All types of estate fields, for example: 'lighting', 'size', 'address', ...
     */
    public function up(): void
    {
        Schema::create('estate_fields', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('estate_field_type_id')->references('id')->on('estate_field_types');
            $table->foreignId('want_estate_field_type_id')->references('id')->on('estate_field_types');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estate_fields');
    }
};
