<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plataformas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100)->unique();
            $table->string('url', 255)->nullable();
            $table->string('icone', 255)->nullable(); // URL do ícone
            $table->boolean('ativa')->default(true);
            $table->timestamps();
        });
        
        DB::table('plataformas')->insert([
            ['nome' => 'LinkedIn', 'url' => 'https://linkedin.com', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Gupy', 'url' => 'https://gupy.io', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Indeed', 'url' => 'https://indeed.com.br', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Catho', 'url' => 'https://catho.com.br', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'InfoJobs', 'url' => 'https://infojobs.com.br', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Trampos', 'url' => 'https://trampos.co', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Email', 'url' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Indicação', 'url' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Site da Empresa', 'url' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Outro', 'url' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('plataformas');
    }
};