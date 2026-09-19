<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('heroes', function (Blueprint $table) {
            // Talent points already allocated by the player. Points earned
            // are derived from level (see HeroLevelingService) rather than
            // stored, so "available" is always earned - spent.
            $table->integer('talent_points_spent')->default(0)->after('xp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('heroes', function (Blueprint $table) {
            $table->dropColumn('talent_points_spent');
        });
    }
};
