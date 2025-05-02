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
        Schema::create('dnadmkap', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('plant_code')->default(null)->nullable();
            $table->string('shop_code')->default(null)->nullable();
            $table->string('part_category')->default(null)->nullable();
            $table->string('route')->default(null)->nullable();
            $table->string('lp')->default(null)->nullable();
            $table->integer('trip')->default(null)->nullable();
            $table->string('vendor_code')->default(null)->nullable();
            $table->string('vendor_alias')->default(null)->nullable();
            $table->string('vendor_site')->default(null)->nullable();
            $table->string('vendor_site_alias')->default(null)->nullable();
            $table->string('order_no')->default(null)->nullable();
            $table->string('po_number')->default(null)->nullable();
            $table->string('calc_date')->default(null)->nullable();
            $table->string('order_date')->default(null)->nullable();
            $table->string('order_time')->default(null)->nullable();
            $table->string('del_date')->default(null)->nullable();
            $table->string('del_time')->default(null)->nullable();
            $table->string('del_cycle')->default(null)->nullable();
            $table->string('doc_no')->default(null)->nullable();
            $table->string('rec_status')->default(null)->nullable();
            $table->string('dn_type')->default(null)->nullable();
            $table->string('rec_date')->default(null)->nullable();
            $table->string('rec_by')->default(null)->nullable();
            $table->string('part_no')->default(null)->nullable();
            $table->string('part_name')->default(null)->nullable();
            $table->string('job_no')->default(null)->nullable();
            $table->integer('lane')->default(null)->nullable();
            $table->integer('qty_kbn')->default(null)->nullable();
            $table->integer('order_kbn')->default(null)->nullable();
            $table->integer('order_pcs')->default(null)->nullable();
            $table->integer('qty_receive')->default(null)->nullable();
            $table->integer('qty_balance')->default(null)->nullable();
            $table->string('cancel_status')->default(null)->nullable();
            $table->string('remark')->default(null)->nullable();
            $table->string('dn_job_no')->unique()->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dnadmkap');
    }
};
