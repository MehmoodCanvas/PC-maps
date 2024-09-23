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
        Schema::create('map', function (Blueprint $table) {
            $table->id('map_id');
            $table->uuid('map_unqiue_id');
            $table->string('map_image');
            $table->text('map_data')->charset('binary');
            $table->string('map_width');
            $table->string('map_height');
            $table->string('map_price');
            $table->string('map_customer_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map');
    }
};
