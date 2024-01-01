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
        Schema::table('register_want_estate_types', function (Blueprint $table) {
            $table->dropForeign(['register_estate_id']);
//            $table->renameColumn('register_estate_id', 'main_register_estate_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main', function (Blueprint $table) {
            //
        });
    }
};
