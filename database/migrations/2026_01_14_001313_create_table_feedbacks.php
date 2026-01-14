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
        Schema::create('table_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidatura_id')
                ->constrained('candidaturas')
                ->cascadeOnDelete();
            $table->foreignId('etapa_id')
                ->nullable()
                ->constrained('etapas_candidatura')
                ->cascadeOnDelete();
            $table->enum('tipo', ['empresa', 'pessoal']);
            $table->text('feedback');
            $table->integer('nota')->nullable(); // 1-5
            $table->text('pontos_fortes')->nullable();
            $table->text('pontos_fracos')->nullable();
            $table->text('aprendizados')->nullable();
            $table->enum('motivo_rejeicao', [
                'perfil_nao_adequado',
                'conhecimento_tecnico',
                'experiencia_insuficiente',
                'pretensao_salarial',
                'soft_skills',
                'vaga_cancelada',
                'outro'
            ])->nullable();
            $table->timestamps();
            $table->index(['candidatura_id', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_feedbacks');
    }
};
