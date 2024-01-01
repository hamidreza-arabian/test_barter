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
            $table->foreignId('employee_id')->default(1)->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('register_want_estate_types', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
    }
};
