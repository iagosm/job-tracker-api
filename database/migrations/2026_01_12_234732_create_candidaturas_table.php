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
              $table->string('cargo', 255);
              $table->string('empresa', 255);
              $table->foreignId('plataforma_id')->nullable()->constrained('plataformas');
              $table->text('link_vaga')->nullable();
              $table->enum('tipo_trabalho', ['remoto', 'presencial', 'hibrido']);
              $table->string('localizacao', 255)->nullable();
              $table->enum('nivel_vaga', ['estagio', 'junior', 'pleno', 'senior', 'especialista'])->nullable();
              $table->decimal('salario_minimo', 10, 2)->nullable();
              $table->decimal('salario_maximo', 10, 2)->nullable();
              $table->boolean('salario_a_combinar')->default(false);
              $table->boolean('curriculo_visualizado')->default(false);
              $table->enum('status', [
                'aplicado',
                'em_analise',
                'triagem',
                'teste_tecnico',
                'entrevista_rh',
                'entrevista_tecnica',
                'entrevista_final',
                'proposta',
                'contratado',
                'rejeitado',
                'desistiu'
              ])->default('aplicado')->index();
              $table->date('data_aplicacao');
              $table->date('data_resposta')->nullable();
              $table->date('data_encerramento')->nullable();
              $table->text('observacoes')->nullable();
              $table->text('requisitos')->nullable();
              $table->timestamps();
              $table->index(['user_id', 'status', 'data_aplicacao']);
              $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
          Schema::dropIfExists('candidaturas');
    }
};
