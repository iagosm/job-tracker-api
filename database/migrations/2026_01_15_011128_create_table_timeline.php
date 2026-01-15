<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timeline_candidatura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidatura_id')
                ->constrained('candidaturas')
                ->cascadeOnDelete();
            $table->enum('tipo_evento', [
                'criacao',
                'mudanca_status',
                'nova_etapa',
                'feedback_recebido',
                'atualizacao',
                'nota_adicionada',
                'exclusao'
            ]);
            $table->string('titulo', 255);
            $table->text('descricao')->nullable();
            $table->json('dados_anteriores')->nullable();
            $table->json('dados_novos')->nullable();
            $table->timestamps();
            $table->index(['candidatura_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timeline_candidatura');
    }
};