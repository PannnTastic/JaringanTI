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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_seen')->nullable()->after('updated_at');
            $table->timestamp('last_location_update')->nullable()->after('last_seen');
            $table->boolean('location_tracking_enabled')->default(true)->after('last_location_update');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_seen',
                'last_location_update',
                'location_tracking_enabled',
            ]);
        });
    }
};
