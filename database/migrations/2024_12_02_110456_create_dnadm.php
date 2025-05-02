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
        Schema::create('dn_adm', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('plant_code')->nullable()->default(null);
            $table->string('shop_code')->nullable()->default(null);
            $table->string('part_category')->nullable()->default(null);
            $table->string('route')->nullable()->default(null);
            $table->string('lp')->nullable()->default(null);
            $table->integer('trip')->nullable()->default(null);
            $table->string('vendor_code')->nullable()->default(null);
            $table->string('vendor_alias')->nullable()->default(null);
            $table->string('vendor_site')->nullable()->default(null);
            $table->string('vendor_site_alias')->nullable()->default(null);
            $table->string('order_no')->nullable()->default(null);
            $table->string('po_number')->nullable()->default(null);
            $table->string('calc_date')->nullable()->default(null);
            $table->string('order_date')->nullable()->default(null);
            $table->string('order_time')->nullable()->default(null);
            $table->string('del_date')->nullable()->default(null);
            $table->string('del_time')->nullable()->default(null);
            $table->string('del_cycle')->nullable()->default(null);
            $table->string('doc_no')->nullable()->default(null);
            $table->string('rec_status')->nullable()->default(null);
            $table->string('dn_type')->nullable()->default(null);
            $table->string('rec_date')->nullable()->default(null);
            $table->string('rec_by')->nullable()->default(null);
            $table->string('part_no')->nullable()->default(null);
            $table->string('part_name')->nullable()->default(null);
            $table->string('job_no')->nullable()->default(null);
            $table->integer('lane')->nullable()->default(null);
            $table->integer('qty_kbn')->nullable()->default(null);
            $table->integer('order_kbn')->nullable()->default(null);
            $table->integer('order_pcs')->nullable()->default(null);
            $table->integer('qty_receive')->nullable()->default(null);
            $table->integer('qty_balance')->nullable()->default(null);
            $table->string('cancel_status')->nullable()->default(null);
            $table->string('remark')->nullable()->default(null);
            $table->string('dn_job_no')->unique()->default(null);
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
