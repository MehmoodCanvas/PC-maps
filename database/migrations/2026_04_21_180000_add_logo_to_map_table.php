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
            $table->string('map_logo')->nullable()->after('map_frame');
            $table->text('map_logo_position')->nullable()->after('map_logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('map', function (Blueprint $table) {
            $table->dropColumn(['map_logo', 'map_logo_position']);
        });
    }
};
