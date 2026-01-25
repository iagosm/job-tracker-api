<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = "feedbacks";
    protected $fillable = [
      'candidatura_id',
      'etapa_id',
      'tipo',
      'feedback ',
      'nota',
      'pontos_fortes',
      'pontos_fracos',
      'aprendizados',
      'motivo_rejeicao',
    ];
}
