<?php

namespace Database\Factories;

use App\Models\Candidatura;
use App\Models\Plataforma;
use App\Models\TimeLineCandidatura;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class CandidaturaFactory extends Factory
{
    protected $model = Candidatura::class;

    public function definition(): array
    {
        $salarioACombinar = $this->faker->boolean(30);

        $salarioMin = $salarioACombinar
            ? null
            : $this->faker->numberBetween(1500, 6000);

        $salarioMax = (!$salarioACombinar && $salarioMin)
            ? $salarioMin + $this->faker->numberBetween(500, 4000)
            : null;

        return [
            'user_id' => User::factory(),
            'plataforma_id' => Plataforma::inRandomOrder()->value('id'),
            'cargo' => $this->faker->jobTitle(),
            'empresa' => $this->faker->company(),
            'link_vaga' => $this->faker->optional()->url(),
            'tipo_trabalho' => $this->faker->randomElement(['remoto','presencial','hibrido']),
            'localizacao' => $this->faker->optional()->city(),
            'nivel_vaga' => $this->faker->randomElement(['estagio','junior','pleno','senior','especialista']),
            'salario_minimo' => $salarioMin,
            'salario_maximo' => $salarioMax,
            'salario_a_combinar' => $salarioACombinar,
            'curriculo_visualizado' => $this->faker->boolean(),
            'status' => $this->faker->randomElement([
                'aplicado','em_analise','triagem','teste_tecnico',
                'entrevista_rh','entrevista_tecnica','entrevista_final',
                'proposta','contratado','rejeitado','desistiu'
            ]),
            'data_aplicacao' => $this->faker->dateTimeBetween('-3 months')->format('Y-m-d'),
            'observacoes' => $this->faker->optional()->paragraph(),
            'requisitos' => $this->faker->optional()->paragraph(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Candidatura $candidatura) {
            // Etapas
            \App\Models\EtapaCandidatura::factory()
                ->count(rand(1, 4))
                ->create(['candidatura_id' => $candidatura->id]);

            // Tecnologias (pivot)
            $tecnologias = \App\Models\Tecnologia::inRandomOrder()
                ->take(rand(2, 5))
                ->get();

            foreach ($tecnologias as $tech) {
                $candidatura->tecnologias()->attach($tech->id, [
                    'nivel' => fake()->randomElement(['basico','intermediario','avancado']),
                    'obrigatoria' => fake()->boolean(),
                ]);
            }

            // Feedbacks
            \App\Models\Feedback::factory()
                ->count(rand(0, 2))
                ->create(['candidatura_id' => $candidatura->id]);

            if ($this->faker->boolean(60)) {
              TimeLineCandidatura::create([
                'candidatura_id' => $candidatura->id,
                'tipo_evento' => 'mudanca_status',
                'titulo' => 'Status atualizado',
                'dados_anteriores' => ['status' => 'aplicado'],
                'dados_novos' => ['status' => $candidatura->status],
              ]);
            }
        });
    }
}
