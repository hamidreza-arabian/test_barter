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
        Schema::create('estate_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_estate_id')->references('id')->on('main_register_estates');
            $table->foreignId('barter_estate_id')->references('id')->on('main_register_estates');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->tinyInteger('barter_type')->comment('[
                0 => dont same,
                1 => same
            ]');
            $table->string('comment');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estate_comments');
    }
};
