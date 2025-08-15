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
         Schema::create('substations', function (Blueprint $table) {
            $table->id('substation_id');
            $table->string('substation_name')->nullable();
            $table->string('substation_feeder')->nullable();
            $table->string('substation_motorized')->nullable();
            $table->string('substation_jarkom')->nullable();
            $table->string('substation_priority')->nullable();
            $table->string('substation_fo')->nullable();
            // $table->string('substation_pop')->nullable();
            $table->string('substation_terdekat')->nullable();
            $table->string('substation_cable_fa')->nullable();
            $table->string('substation_cable_fig')->nullable();
            $table->string('substation_petik_core')->nullable();
            $table->string('substation_work')->nullable();
            $table->string('substation_rab')->nullable();
            $table->string('substation_licensing')->nullable();
            $table->boolean('substation_status')->default(1);
            // bulan
            $table->enum('bulan', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'])->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('pop_id')->constrained('pops', 'pop_id')->onDelete('cascade');
            // $table->foreignId('doc_id')->constrained('documents', 'doc_id')->onDelete('cascade');

        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('substations');
    }
};
