<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vacinas; 

class VacinaSeeder extends Seeder
{
    public function run(): void
    {
        $vacinas = [
            ['nome' => 'Hepatite B', 'descricao' => 'Protege contra Hepatite B', 'validade_meses' => 120],
            ['nome' => 'Tétano', 'descricao' => 'Protege contra tétano', 'validade_meses' => 120],
            ['nome' => 'Influenza', 'descricao' => 'Gripe comum', 'validade_meses' => 12],
            ['nome' => 'COVID-19', 'descricao' => 'Protege contra o coronavírus', 'validade_meses' => 6],
        ];

        foreach ($vacinas as $vacina) {
            Vacinas::create($vacina);
        }
    }
}
