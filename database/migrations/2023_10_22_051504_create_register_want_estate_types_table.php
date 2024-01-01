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
        Schema::create('register_want_estate_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estate_type_id')->references('id')->on('estate_types');
            $table->foreignId('register_estate_id')->references('id')->on('register_estates');
            $table->foreignId('province_id')->references('id')->on('provinces');
            $table->foreignId('city_id')->references('id')->on('cities');
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions');
            $table->unsignedBigInteger('district_id')->nullable();
            $table->foreign('district_id')->references('id')->on('districts');
            $table->tinyInteger('barter_type')->comment('[
                1 => Receive,
                2 => Pay,
                3 => None
            ]');
            $table->bigInteger('barter_price')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_want_estate_types');
    }
};
