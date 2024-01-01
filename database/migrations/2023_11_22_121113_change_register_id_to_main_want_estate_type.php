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
        Schema::table('register_want_estate_fields', function (Blueprint $table) {
            $table->dropForeign(['register_estate_id']);
//            $table->renameColumn('register_estate_id', 'main_register_estate_id');
        });
        Schema::table('register_want_estate_items', function (Blueprint $table) {
            $table->dropForeign(['register_estate_id']);
//            $table->renameColumn('register_estate_id', 'main_register_estate_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('register_want_estate_fields', function (Blueprint $table) {
            $table->dropForeign(['register_estate_id']);
            $table->renameColumn('main_register_estate_id', 'register_estate_id');
        });
        Schema::table('register_want_estate_items', function (Blueprint $table) {
            $table->renameColumn('main_register_estate_id', 'register_estate_id');
        });
    }
};
