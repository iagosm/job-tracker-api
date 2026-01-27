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

    public function scopeFilter($query, array $filters)
    {
        $orderBy = $filters['order_by'] ?? 'data_aplicacao';
        $orderDir = $filters['order_dir'] ?? 'desc';

        return $query
            ->when($filters['tipo_trabalho'] ?? null,
                fn ($q, $v) => $q->where('tipo_trabalho', $v))
            ->when($filters['empresa'] ?? null,
                fn ($q, $v) => $q->where('empresa', 'like', "%{$v}%"))
            ->when($filters['nivel_vaga'] ?? null,
                fn ($q, $v) => $q->where('nivel_vaga', $v))
            ->when($filters['plataforma_id'] ?? null,
                fn ($q, $v) => $q->where('plataforma_id', (int) $v))
            ->when($filters['salario_min'] ?? null,
                fn ($q, $v) => $q->where('salario_minimo', '>=', (float) $v))
            ->when($filters['salario_max'] ?? null,
                fn ($q, $v) => $q->where('salario_maximo', '<=', (float) $v))
            ->when($filters['status'] ?? null,
                fn ($q, $v) => $q->where('status', $v))
            ->orderBy($orderBy, $orderDir);
    }

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
