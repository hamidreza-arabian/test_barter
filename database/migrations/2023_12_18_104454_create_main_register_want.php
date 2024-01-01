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
        Schema::create('main_register_wants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_register_estate_id');
            $table->tinyInteger('barter_type')->comment('[
                1 => Receive,
                2 => Pay,
                3 => None
            ]');
            $table->bigInteger('barter_price')->nullable();
            $table->foreignId('employee_id')->references('id')->on('users');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_register_want');
    }
};
