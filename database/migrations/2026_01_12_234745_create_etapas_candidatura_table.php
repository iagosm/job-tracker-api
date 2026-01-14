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
            $table->foreignId('candidatura_id')
                ->constrained('candidaturas')
                ->cascadeOnDelete();
            $table->enum('tipo_etapa', [
                'triagem',
                'teste_tecnico',
                'teste_pratico',
                'entrevista_rh',
                'entrevista_tecnica',
                'entrevista_gestor',
                'entrevista_final',
                'proposta',
                'outro'
            ]);
            $table->string('titulo', 255);
            $table->text('descricao')->nullable();
            $table->enum('status', [
                'agendado',
                'em_andamento',
                'concluido',
                'aprovado',
                'reprovado',
                'cancelado'
            ])->default('agendado');
            $table->dateTime('data_agendada')->nullable();
            $table->dateTime('data_realizada')->nullable();
            $table->dateTime('data_resposta')->nullable();
            $table->text('feedback_empresa')->nullable();
            $table->text('feedback_pessoal')->nullable();
            $table->integer('nota_auto_avaliacao')->nullable();
            $table->string('entrevistadores')->nullable();
            $table->integer('duracao_minutos')->nullable();
            $table->timestamps();
            $table->index(['candidatura_id', 'status']);
            $table->index(['candidatura_id', 'data_agendada']);
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
