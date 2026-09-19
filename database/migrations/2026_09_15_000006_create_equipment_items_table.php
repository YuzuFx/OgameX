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
        Schema::create('equipment_items', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            // arme / armure / accessoire — plain string for now, slot list
            // to be finalized alongside the craft/forge system (GDD 5.7).
            $table->string('slot');

            // Backed by OGame\Enums\EquipmentRarity.
            $table->unsignedTinyInteger('rarity')->default(1);

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_items');
    }
};
