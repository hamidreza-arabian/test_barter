<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The type of fields of each type of estate, for example: for the apartment 'lighting' and the 'floors', ...
     */
    public function up(): void
    {
        Schema::create('estate_type_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estate_type_id')->references('id')->on('estate_types');
            $table->foreignId('estate_field_id')->references('id')->on('estate_fields');
            $table->integer('score')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estate_type_fields');
    }
};
