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
        Schema::create('gardus', function (Blueprint $table) {
            $table->id('gardus_id');
            $table->string('gardu_name');
            $table->string('gardu_feeder');
            $table->string('gardu_motorized');
            $table->string('gardu_jarkom');
            $table->string('gardu_proritas');
            $table->string('gardu_fo');
            $table->string('gardu_pop');
            $table->string('gardu_terdekat');
            $table->string('gardu_kabel_fa');
            $table->string('gardu_kabel_fig');
            $table->string('gardu_petik_core');
            $table->string('gardu_pekerjaan');
            $table->string('gardu_rab');
            $table->string('gardu_perizinan');
            $table->boolean('gardus_status')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gardus');
    }
};
