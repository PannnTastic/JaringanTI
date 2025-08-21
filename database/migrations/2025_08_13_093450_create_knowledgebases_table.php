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
        Schema::create('knowledgebase', function (Blueprint $table) {
            $table->id('kb_id');
            $table->string('kb_name');
            $table->text('kb_content');
            $table->boolean('kb_status')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('field_id')->constrained('field')->references('field_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledgebase');
    }
};
