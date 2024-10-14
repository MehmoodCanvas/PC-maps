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
        Schema::create('order', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('order_invoice_id');
            $table->string('order_address_one');
            $table->string('order_address_two');
            $table->string('order_zip_code');
            $table->enum('order_payment_status',['Paid','Not-Paid']);
            $table->enum('order_status',['Approved','Not-Approved','Refunded','Cancelled']);
            $table->integer('order_member_id');
            $table->integer('order_total_amount');
            $table->integer('order_map_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
