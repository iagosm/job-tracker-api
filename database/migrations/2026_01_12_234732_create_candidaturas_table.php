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
        Schema::create('candidaturas', function (Blueprint $table) {
             $table->id();
              $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();
              $table->string('nome_vaga', 255);
              $table->string('empresa', 255);
              $table->string('plataforma', 255);
              $table->text('link_vaga')->nullable();
              $table->enum('tipo_trabalho', ['remoto', 'presencial', 'hibrido']);
              $table->string('localizacao')->nullable();
              $table->boolean('curriculo_visualizado')->default(false);
              $table->enum('status', [
                  'aplicado',
                  'em_analise',
                  'entrevista',
                  'proposta',
                  'contratado',
                  'rejeitado',
                  'desistiu'
              ])->default('aplicado');
              $table->date('data_aplicacao');
              $table->text('observacoes')->nullable();
              $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidaturas');
    }
};
