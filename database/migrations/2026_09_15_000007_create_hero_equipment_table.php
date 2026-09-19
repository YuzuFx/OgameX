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
        Schema::create('hero_equipment', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hero_id')->constrained('heroes')->cascadeOnDelete();
            $table->foreignId('equipment_item_id')->constrained('equipment_items')->cascadeOnDelete();

            // Whether this item is currently worn (equipped slot) vs sitting
            // in the Diablo-style inventory grid.
            $table->boolean('equipped')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_equipment');
    }
};
