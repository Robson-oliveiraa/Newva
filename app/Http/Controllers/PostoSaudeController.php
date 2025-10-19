<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PostoSaudeController extends Controller
{
    /**
     * Display the health centers map page
     */
    public function index(): View
    {
        // Coordenadas dos principais postos de saúde em Porto Velho
        $postos = [
            [
                'nome' => 'UPA Zona Sul',
                'endereco' => 'Rua das Palmeiras, 1234 - Zona Sul',
                'telefone' => '(69) 3214-5678',
                'horario' => '24h',
                'lat' => -8.7612,
                'lng' => -63.9020,
                'tipo' => 'UPA'
            ],
            [
                'nome' => 'UBS São Francisco',
                'endereco' => 'Av. Presidente Vargas, 567 - São Francisco',
                'telefone' => '(69) 3214-5679',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7580,
                'lng' => -63.9000,
                'tipo' => 'UBS'
            ],
            [
                'nome' => 'UBS Cohab',
                'endereco' => 'Rua dos Coqueiros, 890 - Cohab',
                'telefone' => '(69) 3214-5680',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7650,
                'lng' => -63.8950,
                'tipo' => 'UBS'
            ],
            [
                'nome' => 'UBS Tancredo Neves',
                'endereco' => 'Av. Tancredo Neves, 123 - Tancredo Neves',
                'telefone' => '(69) 3214-5681',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7500,
                'lng' => -63.8900,
                'tipo' => 'UBS'
            ],
            [
                'nome' => 'Hospital de Base Dr. Ary Pinheiro',
                'endereco' => 'Av. Carlos Gomes, 1000 - Centro',
                'telefone' => '(69) 3214-5682',
                'horario' => '24h',
                'lat' => -8.7600,
                'lng' => -63.9050,
                'tipo' => 'Hospital'
            ],
            [
                'nome' => 'UBS Nova Porto Velho',
                'endereco' => 'Rua das Flores, 456 - Nova Porto Velho',
                'telefone' => '(69) 3214-5683',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7400,
                'lng' => -63.8800,
                'tipo' => 'UBS'
            ],
            [
                'nome' => 'UBS Cidade Nova',
                'endereco' => 'Av. Jatuarana, 789 - Cidade Nova',
                'telefone' => '(69) 3214-5684',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7700,
                'lng' => -63.9100,
                'tipo' => 'UBS'
            ],
            [
                'nome' => 'UPA Zona Norte',
                'endereco' => 'Rua dos Ipês, 321 - Zona Norte',
                'telefone' => '(69) 3214-5685',
                'horario' => '24h',
                'lat' => -8.7800,
                'lng' => -63.9200,
                'tipo' => 'UPA'
            ]
        ];

        return view('postos-saude.index', compact('postos'));
    }
}


