<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidatura extends Model
{
    use HasFactory;  
    protected $table = "candidaturas"; 

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
      return $this->belongsTo(EtapaCandidatura::class);
    }

    public function plataforma()
    {
      return $this->belongsTo(Plataforma::class);
    }
}
