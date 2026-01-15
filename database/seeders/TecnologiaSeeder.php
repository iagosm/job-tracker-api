<?php

namespace Database\Seeders;

use App\Models\Tecnologia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TecnologiaSeeder extends Seeder
{
    public function run(): void
    {
        $tecnologias = [
            ['nome' => 'PHP', 'categoria' => 'backend'],
            ['nome' => 'Laravel', 'categoria' => 'backend'],
            ['nome' => 'JavaScript', 'categoria' => 'frontend'],
            ['nome' => 'Vue', 'categoria' => 'frontend'],
            ['nome' => 'React', 'categoria' => 'frontend'],
            ['nome' => 'MySQL', 'categoria' => 'database'],
        ];
        foreach ($tecnologias as $tec) {
            Tecnologia::firstOrCreate(
                ['nome' => $tec['nome']],
                $tec
            );
        }
    }
}

