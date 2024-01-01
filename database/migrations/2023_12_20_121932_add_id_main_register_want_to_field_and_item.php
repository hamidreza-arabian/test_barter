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
        Schema::table('register_want_estate_items', function (Blueprint $table) {
            $table->foreignId('main_register_want_id');

        });
        Schema::table('register_want_estate_fields', function (Blueprint $table) {
            $table->foreignId('main_register_want_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('field_and_item', function (Blueprint $table) {
            //
        });
    }
};
