<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeLineCandidatura extends Model
{
    protected $table = "timeline_candidatura";
    protected $fillable = [
      'candidatura_id',
      'tipo_evento',
      'titulo',
      'descricao',
      'dados_anteriores',
      'dados_novos',
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
    ];
}
