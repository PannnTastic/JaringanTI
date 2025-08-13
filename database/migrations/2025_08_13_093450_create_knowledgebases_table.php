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
            $table->foreignId('kbc_id')->constrained('knowledgebase_category')->references('kbc_id')->onDelete('cascade');
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
