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
        Schema::create('pccs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('del_from');
            $table->string('del_to');
            $table->string('supply_address');
            $table->string('m/s_id');
            $table->string('inven_category');
            $table->string('part_no');
            $table->string('part_name');
            $table->string('p/s_code');
            $table->integer('order_class');
            $table->unsignedBigInteger('prod_seq_no')->nullable();
            $table->string('kd_lot_no');
            $table->unsignedBigInteger('slip_no');
            $table->integer('pcc_count');
            $table->integer('qty_packing');
            $table->integer('ship');
            $table->string('slip_barcode');
            $table->boolean('isMatch')->default(false);
            $table->date('date');
            $table->time('time');
            $table->string('hns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pccs');
    }
};
