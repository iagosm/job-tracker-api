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
        Schema::create('candidatura_tecnologia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidatura_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tecnologia_id')->constrained()->cascadeOnDelete();
            $table->enum('nivel', ['basico', 'intermediario', 'avancado']);
            $table->boolean('obrigatoria')->default(true);
            $table->timestamps();
            $table->unique(['candidatura_id', 'tecnologia_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatura_tecnologia');
    }
};
