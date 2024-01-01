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
        Schema::table('register_estates', function (Blueprint $table) {
//            $table->renameColumn('active', 'status');
            $table->foreignId('main_register_estate_id')->references('id')->on('main_register_estates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('register_estates', function (Blueprint $table) {
//            $table->renameColumn('status', 'active');
            $table->dropForeign('main_register_es   tate_id');
        });
    }
};
