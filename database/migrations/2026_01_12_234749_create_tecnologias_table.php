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
        Schema::create('tecnologias', function (Blueprint $table) {
             $table->id();
            $table->string('nome', 100)->unique()->index(); // Indexado para busca
            $table->string('categoria', 50)->nullable(); // backend, frontend, database, devops
            $table->string('icone', 255)->nullable(); // URL do ícone
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tecnologias');
    }
};
