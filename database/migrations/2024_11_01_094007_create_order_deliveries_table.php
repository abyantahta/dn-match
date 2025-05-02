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
        Schema::create('order_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('plant_code');
            $table->string('shop_code');
            $table->string('part_category');
            $table->string('route');
            $table->string('lp');
            $table->integer('trip');
            $table->string('vendor_code');
            $table->string('vendor_alias');
            $table->string('vendor_site');
            $table->string('vendor_site_alias');
            $table->string('order_no');
            $table->string('po_number');
            $table->date('calc_date');
            $table->date('order_date');
            $table->time('order_time');
            $table->date('del_date');
            $table->time('del_time');
            $table->string('del_cycle');
            $table->string('doc_no');
            $table->string('rec_status');
            $table->string('dn_type');
            $table->date('rec_date')->nullable();
            $table->string('rec_by')->nullable();
            $table->string('part_no');
            $table->string('part_name');
            $table->string('job_no');
            $table->integer('lane');
            $table->integer('qty_kbn');
            $table->integer('order_kbn');
            $table->integer('order_pcs');
            $table->integer('qty_receive');
            $table->integer('qty_balance');
            $table->string('cancel_status');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_deliveries');
    }
};
