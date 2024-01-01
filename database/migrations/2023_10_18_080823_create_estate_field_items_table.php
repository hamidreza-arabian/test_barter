<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Items of a estate when they are checkbox, radio button and ...
     */
    public function up(): void
    {
        Schema::create('estate_field_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estate_field_id')->references('id')->on('estate_fields');
            $table->string('title');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estate_field_items');
    }
};
