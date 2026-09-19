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
            // Primary attributes (Diablo-style character sheet reference).
            $table->unsignedInteger('strength')->default(0)->after('status');
            $table->unsignedInteger('intelligence')->default(0)->after('strength');
            $table->unsignedInteger('willpower')->default(0)->after('intelligence');
            $table->unsignedInteger('dexterity')->default(0)->after('willpower');

            // Derived combat stats, headlined on the sheet. Stored flat for
            // now: the formula deriving these from the primary attributes
            // (and their growth per level/archetype) is still TBD (Phase 6).
            $table->unsignedInteger('attack_power')->default(0)->after('dexterity');
            $table->unsignedInteger('armor')->default(0)->after('attack_power');
            $table->unsignedInteger('life')->default(0)->after('armor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('heroes', function (Blueprint $table) {
            $table->dropColumn(['strength', 'intelligence', 'willpower', 'dexterity', 'attack_power', 'armor', 'life']);
        });
    }
};
