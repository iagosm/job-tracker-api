<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidatura extends Model
{
    use HasFactory;

    protected $table = 'candidaturas';

   protected $fillable = [
        'user_id',
        'cargo',
        'empresa',
        'plataforma_id',
        'link_vaga',
        'tipo_trabalho',
        'localizacao',
        'nivel_vaga',
        'salario_minimo',
        'salario_maximo',
        'salario_a_combinar',
        'curriculo_visualizado',
        'status',
        'data_aplicacao',
        'data_resposta',
        'data_encerramento',
        'observacoes',
        'requisitos'
    ];

    protected $casts = [
        'data_aplicacao' => 'date',
        'data_resposta' => 'date',
        'data_encerramento' => 'date',
        'salario_minimo' => 'decimal:2',
        'salario_maximo' => 'decimal:2',
        'curriculo_visualizado' => 'boolean',
        'salario_a_combinar' => 'boolean',
    ];

    public function tecnologias()
    {
        return $this->belongsToMany(Tecnologia::class, 'candidatura_tecnologia')
            ->withPivot(['nivel', 'obrigatoria'])
            ->withTimestamps();
    }

    public function etapas()
    {
        return $this->hasMany(EtapaCandidatura::class);
    }

    public function feedbacks()
    {

        return $this->hasMany(Feedback::class);
    }

    public function plataforma()
    {
        return $this->belongsTo(Plataforma::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeline()
    {
        return $this->hasMany(TimeLineCandidatura::class);
    }
}
