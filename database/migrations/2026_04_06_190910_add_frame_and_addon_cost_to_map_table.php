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
        Schema::table('map', function (Blueprint $table) {
            $table->string('map_frame')->nullable()->after('map_image');
            $table->decimal('map_base_cost', 10, 2)->default(0)->after('map_price');
            $table->decimal('map_addon_cost', 10, 2)->default(0)->after('map_base_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('map', function (Blueprint $table) {
            //
        });
    }
};
