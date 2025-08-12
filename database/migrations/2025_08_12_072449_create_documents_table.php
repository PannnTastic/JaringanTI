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
        Schema::create('documents', function (Blueprint $table) {
            // doc_id, doc_name,doc_status,doc_file
            $table->id('doc_id');
            $table->string('doc_name');
            $table->string('doc_file');
            $table->string('doc_status');
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
        Schema::dropIfExists('documents');
    }
};
