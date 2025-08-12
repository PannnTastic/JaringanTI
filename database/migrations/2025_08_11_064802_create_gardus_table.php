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
            $table->id('gardus_id')->nullable();
            $table->string('gardu_name')->nullable();
            $table->string('gardu_feeder')->nullable();
            $table->string('gardu_motorized')->nullable();
            $table->string('gardu_jarkom')->nullable();
            $table->string('gardu_proritas')->nullable();
            $table->string('gardu_fo')->nullable();
            $table->string('gardu_pop')->nullable();
            $table->string('gardu_terdekat')->nullable();
            $table->string('gardu_kabel_fa')->nullable();
            $table->string('gardu_kabel_fig')->nullable();
            $table->string('gardu_petik_core')->nullable();
            $table->string('gardu_pekerjaan')->nullable();
            $table->string('gardu_rab')->nullable();
            $table->string('gardu_perizinan')->nullable();
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
