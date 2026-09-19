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
        Schema::create('hero_talents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hero_id')->constrained('heroes')->cascadeOnDelete();
            $table->foreignId('talent_node_id')->constrained('talent_nodes')->cascadeOnDelete();

            $table->unsignedTinyInteger('rank')->default(1);

            $table->timestamps();

            $table->unique(['hero_id', 'talent_node_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_talents');
    }
};
