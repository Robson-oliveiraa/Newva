<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vacinas; 

class VacinaSeeder extends Seeder
{
    public function run(): void
    {
        $vacinas = [
            ['nome' => 'BCG', 'descricao' => 'Vacina contra tuberculose', 'validade_meses' => 12],
            ['nome' => 'Hepatite B', 'descricao' => 'Vacina contra hepatite B', 'validade_meses' => 6],
            ['nome' => 'Tríplice Viral', 'descricao' => 'Vacina contra sarampo, caxumba e rubéola', 'validade_meses' => 12],
            ['nome' => 'DTP', 'descricao' => 'Vacina contra difteria, tétano e coqueluche', 'validade_meses' => 10],
            ['nome' => 'Poliomielite', 'descricao' => 'Vacina contra poliomielite', 'validade_meses' => 12],
            ['nome' => 'Febre Amarela', 'descricao' => 'Vacina contra febre amarela', 'validade_meses' => 10],
            ['nome' => 'Hepatite A', 'descricao' => 'Vacina contra hepatite A', 'validade_meses' => 6],
            ['nome' => 'Varicela', 'descricao' => 'Vacina contra varicela (catapora)', 'validade_meses' => 12],
            ['nome' => 'HPV', 'descricao' => 'Vacina contra papilomavírus humano', 'validade_meses' => 6],
            ['nome' => 'Influenza', 'descricao' => 'Vacina contra gripe', 'validade_meses' => 12],
            ['nome' => 'COVID-19', 'descricao' => 'Vacina contra coronavírus', 'validade_meses' => 6],
        ];

        foreach ($vacinas as $vacina) {
            Vacinas::create($vacina);
        }
    }
}
