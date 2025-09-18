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
        Schema::table('locations', function (Blueprint $table) {
            // Fix the typo: longtitude -> longitude
            if (Schema::hasColumn('locations', 'longtitude')) {
                $table->renameColumn('longtitude', 'longitude');
            }

            // Change data types to be consistent with users table
            $table->decimal('latitude', 10, 8)->nullable()->change();
            $table->decimal('longitude', 11, 8)->nullable()->change();

            // Add missing columns if they don't exist
            if (! Schema::hasColumn('locations', 'description')) {
                $table->text('description')->nullable()->after('location');
            }

            if (! Schema::hasColumn('locations', 'address')) {
                $table->text('address')->nullable()->after('description');
            }

            if (! Schema::hasColumn('locations', 'status')) {
                $table->boolean('status')->default(true)->after('address');
            }

            // Add index for better performance
            $table->index(['latitude', 'longitude']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Remove indexes
            $table->dropIndex(['latitude', 'longitude']);
            $table->dropIndex(['status']);

            // Remove added columns
            if (Schema::hasColumn('locations', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('locations', 'address')) {
                $table->dropColumn('address');
            }

            if (Schema::hasColumn('locations', 'status')) {
                $table->dropColumn('status');
            }

            // Revert data types
            $table->double('latitude')->nullable()->change();
            $table->double('longitude')->nullable()->change();

            // Rename back to typo (if needed for rollback)
            if (Schema::hasColumn('locations', 'longitude')) {
                $table->renameColumn('longitude', 'longtitude');
            }
        });
    }
};
