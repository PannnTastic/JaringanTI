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
        Schema::create('approvers', function (Blueprint $table) {
            $table->foreignId('permit_id')->constrained('permits', 'permit_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->boolean('approver_status')->default(0);
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvers');
    }
};
