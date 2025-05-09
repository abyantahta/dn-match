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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('slip_barcode')->nullable();
            $table->string('part_no_pcc')->nullable();
            $table->string('part_no_fg')->nullable();
            $table->string('seq_fg')->nullable();
            $table->enum('status',['paired','match','mismatch','not_match']);
            $table->timestamps();
            // $table->integer('order_kbn')->nullable();
            // $table->integer('match_kbn')->nullable();
            // $table->string('dn_status')->nullable();
            // $table->id();
            // $table->string('barcode_cust')->nullable();
            // $table->string('no_dn')->nullable();
            // $table->string('no_job')->nullable();
            // $table->string('no_seq')->nullable();
            // $table->string('barcode_fg')->nullable();
            // $table->string('no_job_fg')->nullable();
            // $table->string('no_seq_fg')->nullable();
            // $table->string('plant')->nullable();
            // $table->integer('order_kbn')->nullable();
            // $table->integer('match_kbn')->nullable();
            // $table->enum('status',['paired','match','mismatch','not_match']);
            // $table->string('del_cycle');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard');
    }
};
