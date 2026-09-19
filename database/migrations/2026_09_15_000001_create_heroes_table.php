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
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();

            // Foreign key to the user (player) that recruited this hero.
            $table->integer('user_id', false, true);
            $table->foreign('user_id')->references('id')->on('users');

            // Foreign key to the planet this hero governs. Nullable because a
            // recruited hero is not necessarily assigned as governor yet.
            $table->integer('planet_id', false, true)->nullable();
            $table->foreign('planet_id')->references('id')->on('planets');

            $table->string('name');

            // Placeholder value until the archetype list is finalized in the GDD.
            $table->string('archetype')->default('unknown');

            $table->integer('level')->default(1);
            $table->integer('xp')->default(0);

            // available / on_mission / unavailable
            $table->string('status')->default('available');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heroes');
    }
};
