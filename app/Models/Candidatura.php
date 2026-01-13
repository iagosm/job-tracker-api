<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidatura extends Model
{
    protected $table = "candidaturas"; 
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
