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
        Schema::create('dnadmkep', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('dn_no');
            $table->string('vendor_no');
            $table->string('vendor_alias');
            $table->string('vendor_site');
            $table->string('vendor_site_alias');
            $table->string('trans_code');
            $table->string('trans_route');
            $table->date('anbunka_date');
            $table->string('anbunka_shift');
            $table->string('order_no');
            $table->date('order_date');
            $table->time('order_time');
            $table->integer('order_cycle');
            $table->string('shop_code');
            $table->string('plant_code');
            $table->string('part_cat');
            $table->string('wh_no');
            $table->string('del_type');
            $table->date('del_date');
            $table->time('del_time');
            $table->string('del_cycle');
            $table->string('dock_no');
            $table->string('park_no');
            $table->integer('total_kanban');
            $table->string('printed');
            $table->string('receive_status');
            $table->string('dn_type');
            $table->string('receive_method');
            $table->date('receive_date');
            $table->string('receive_by')->nullable();
            $table->string('status');
            $table->integer('no');
            $table->integer('ext_core');
            $table->string('part_no');
            $table->string('part_name');
            $table->string('job_no');
            $table->integer('qty_box');
            $table->integer('qty_kanban');
            $table->integer('qty_order');
            $table->integer('qty_recieve');
            $table->integer('qty_balance');
            $table->string('partial_status');
            $table->date('updated_date');
            $table->date('disable_date');
            $table->string('order_status');
            $table->string('dn_job_no')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dnadmkep');
    }
};
