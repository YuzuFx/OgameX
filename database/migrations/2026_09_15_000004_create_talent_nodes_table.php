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
        Schema::create('talent_nodes', function (Blueprint $table) {
            $table->id();

            // Matches Hero.archetype (plain string for now — no separate
            // archetype table yet, the GDD roster of archetypes is still TBD).
            $table->string('archetype');

            $table->string('name');
            $table->text('description')->nullable();

            // Tier gates the talent behind prior investment in the tree
            // (World of Warcraft-style). Not enforced yet — structure only.
            $table->unsignedTinyInteger('tier')->default(1);
            $table->unsignedTinyInteger('point_cost')->default(1);
            $table->unsignedTinyInteger('max_rank')->default(1);

            // Optional prerequisite node within the same archetype's tree.
            $table->foreignId('prerequisite_talent_node_id')->nullable()->constrained('talent_nodes')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talent_nodes');
    }
};
