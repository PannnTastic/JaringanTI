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
            $table->decimal('latitude', 10, 8)->nullable()->after('user_photo');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->text('address')->nullable()->after('longitude');

            // Add indexes for better performance when querying locations
            $table->index(['latitude', 'longitude']);
            $table->index('updated_at'); // For finding recent location updates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['latitude', 'longitude']);
            $table->dropIndex(['updated_at']);
            $table->dropColumn(['latitude', 'longitude', 'address']);
        });
    }
};
