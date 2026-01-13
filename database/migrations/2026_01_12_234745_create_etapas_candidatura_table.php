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
        Schema::create('etapas_candidatura', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('candidatura_id')
              ->constrained('candidaturas')
              ->onDelete('cascade');
            $table->string('titulo_etapa',  255);
            $table->string('descricao',  255);
            $table->string('status');
            $table->date('data_etapaa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etapas_candidatura');
    }
};
