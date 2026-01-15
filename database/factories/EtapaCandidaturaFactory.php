<?php

namespace Database\Factories;

use App\Models\EtapaCandidatura;
use App\Models\Candidatura;
use Illuminate\Database\Eloquent\Factories\Factory;

class EtapaCandidaturaFactory extends Factory
{
    protected $model = EtapaCandidatura::class;

    public function definition(): array
    {
        $tipoEtapa = $this->faker->randomElement([
            'triagem','teste_tecnico','teste_pratico','entrevista_rh','entrevista_tecnica','entrevista_final'
        ]);
        $status = $this->faker->randomElement(['agendado','em_andamento','concluido','aprovado','reprovado']);
        $dataAgendada = $this->faker->optional(0.7)->dateTimeBetween('-15 days','+15 days');
        $dataRealizada = null;
        if (in_array($status, ['concluido','aprovado','reprovado'])) {
            $dataRealizada = $dataAgendada && $dataAgendada <= now()
                ? $this->faker->dateTimeBetween($dataAgendada,'now')
                : $this->faker->dateTimeBetween('-10 days','now');
        }
        return [
            'candidatura_id' => Candidatura::inRandomOrder()->value('id'),
            'tipo_etapa' => $tipoEtapa,
            'titulo' => match ($tipoEtapa) {
                'triagem' => 'Triagem Inicial',
                'teste_tecnico' => 'Teste Técnico',
                'teste_pratico' => 'Teste Prático',
                'entrevista_rh' => 'Entrevista com RH',
                'entrevista_tecnica' => 'Entrevista Técnica',
                'entrevista_final' => 'Entrevista Final',
            },
            'descricao' => match ($tipoEtapa) {
                'triagem' => 'Triagem inicial do candidato.',
                'teste_tecnico' => 'Avaliação prática de conhecimentos técnicos.',
                'teste_pratico' => 'Execução de atividade prática.',
                'entrevista_rh' => 'Entrevista de alinhamento cultural e comportamental.',
                'entrevista_tecnica' => 'Entrevista técnica com foco em habilidades específicas.',
                'entrevista_final' => 'Entrevista final com o gestor da área.',
            },
            'status' => $status,
            'data_agendada' => $dataAgendada,
            'data_realizada' => $dataRealizada,
            'data_resposta' => in_array($status,['aprovado','reprovado'])
                ? $this->faker->dateTimeBetween($dataRealizada ?? '-5 days','now')
                : null,
            'feedback_empresa' => in_array($status,['reprovado'])
                ? $this->faker->sentence(10,true)
                : null,
            'feedback_pessoal' => in_array($status,['concluido','aprovado','reprovado'])
                ? $this->faker->sentence(10,true)
                : null,
            'nota_auto_avaliacao' => $this->faker->optional()->numberBetween(1,10),
            'entrevistadores' => $this->faker->optional()->name() . ', ' . $this->faker->name(),
            'duracao_minutos' => $this->faker->optional()->randomElement([30,45,60,90]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
