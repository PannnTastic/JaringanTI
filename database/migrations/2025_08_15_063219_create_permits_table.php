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
        Schema::create('permits', function (Blueprint $table) {
            $table->id('permit_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->foreignId('substation_id')->constrained('substations', 'substation_id');
            $table->boolean('permit_status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permits');
    }
};
