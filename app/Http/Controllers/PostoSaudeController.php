<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\DemasApiController;

class PostoSaudeController extends Controller
{
    protected $demasApi;

    public function __construct(DemasApiController $demasApi)
    {
        $this->demasApi = $demasApi;
    }

    /**
     * Display the health centers map page
     */
    public function index(): View
    {
        try {
            // Tenta buscar dados da API da DEMAS
            $response = $this->demasApi->getUnidadesSaude();
            
            if ($response->getStatusCode() === 200) {
                $data = $response->getData(true);
                $postos = $data['data'] ?? [];
            } else {
                // Fallback para dados estáticos se a API falhar
                $postos = $this->getDadosEstaticos();
            }
        } catch (\Exception $e) {
            // Fallback para dados estáticos em caso de erro
            $postos = $this->getDadosEstaticos();
        }

        return view('postos-saude.index', compact('postos'));
    }

    /**
     * Busca postos por bairro
     */
    public function porBairro(Request $request, $bairro)
    {
        try {
            $response = $this->demasApi->getUnidadesPorBairro($bairro);
            
            if ($response->getStatusCode() === 200) {
                $data = $response->getData(true);
                $postos = $data['data'] ?? [];
            } else {
                $postos = $this->getDadosEstaticos($bairro);
            }
        } catch (\Exception $e) {
            $postos = $this->getDadosEstaticos($bairro);
        }

        return view('postos-saude.index', compact('postos', 'bairro'));
    }

    /**
     * Busca postos próximos
     */
    public function proximos(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'raio' => 'nullable|numeric|min:0.1|max:50'
        ]);

        try {
            $response = $this->demasApi->getUnidadesProximas($request);
            
            if ($response->getStatusCode() === 200) {
                $data = $response->getData(true);
                $postos = $data['data'] ?? [];
            } else {
                $postos = $this->getDadosEstaticosProximos(
                    $request->latitude, 
                    $request->longitude, 
                    $request->raio ?? 5
                );
            }
        } catch (\Exception $e) {
            $postos = $this->getDadosEstaticosProximos(
                $request->latitude, 
                $request->longitude, 
                $request->raio ?? 5
            );
        }

        return view('postos-saude.index', compact('postos'));
    }

    /**
     * Mostra detalhes de um posto específico
     */
    public function show($id)
    {
        try {
            $response = $this->demasApi->getUnidadeDetalhes($id);
            
            if ($response->getStatusCode() === 200) {
                $data = $response->getData(true);
                $posto = $data['data'] ?? null;
            } else {
                $posto = $this->getDadosEstaticos()[$id - 1] ?? null;
            }
        } catch (\Exception $e) {
            $posto = $this->getDadosEstaticos()[$id - 1] ?? null;
        }

        if (!$posto) {
            abort(404, 'Posto de saúde não encontrado');
        }

        return view('postos-saude.show', compact('posto'));
    }

    /**
     * Dados estáticos como fallback
     */
    private function getDadosEstaticos($bairro = null)
    {
        $postos = [
            [
                'id' => 1,
                'nome' => 'UPA Zona Sul',
                'endereco' => 'Rua das Palmeiras, 1234 - Zona Sul',
                'telefone' => '(69) 3214-5678',
                'horario' => '24h',
                'lat' => -8.7612,
                'lng' => -63.9020,
                'tipo' => 'UPA',
                'bairro' => 'Zona Sul',
                'especialidades' => ['Emergência', 'Clínica Geral'],
                'servicos' => ['Atendimento 24h', 'Exames', 'Medicamentos']
            ],
            [
                'id' => 2,
                'nome' => 'UBS São Francisco',
                'endereco' => 'Av. Presidente Vargas, 567 - São Francisco',
                'telefone' => '(69) 3214-5679',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7580,
                'lng' => -63.9000,
                'tipo' => 'UBS',
                'bairro' => 'São Francisco',
                'especialidades' => ['Clínica Geral', 'Pediatria', 'Ginecologia'],
                'servicos' => ['Consultas', 'Vacinação', 'Exames']
            ],
            [
                'id' => 3,
                'nome' => 'UBS Cohab',
                'endereco' => 'Rua dos Coqueiros, 890 - Cohab',
                'telefone' => '(69) 3214-5680',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7650,
                'lng' => -63.8950,
                'tipo' => 'UBS',
                'bairro' => 'Cohab',
                'especialidades' => ['Clínica Geral', 'Odontologia'],
                'servicos' => ['Consultas', 'Vacinação', 'Odontologia']
            ],
            [
                'id' => 4,
                'nome' => 'UBS Tancredo Neves',
                'endereco' => 'Av. Tancredo Neves, 123 - Tancredo Neves',
                'telefone' => '(69) 3214-5681',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7500,
                'lng' => -63.8900,
                'tipo' => 'UBS',
                'bairro' => 'Tancredo Neves',
                'especialidades' => ['Clínica Geral', 'Pediatria'],
                'servicos' => ['Consultas', 'Vacinação']
            ],
            [
                'id' => 5,
                'nome' => 'Hospital de Base Dr. Ary Pinheiro',
                'endereco' => 'Av. Carlos Gomes, 1000 - Centro',
                'telefone' => '(69) 3214-5682',
                'horario' => '24h',
                'lat' => -8.7600,
                'lng' => -63.9050,
                'tipo' => 'Hospital',
                'bairro' => 'Centro',
                'especialidades' => ['Emergência', 'Cirurgia', 'Cardiologia', 'Neurologia'],
                'servicos' => ['Atendimento 24h', 'Cirurgias', 'Exames Complexos', 'Internação']
            ],
            [
                'id' => 6,
                'nome' => 'UBS Nova Porto Velho',
                'endereco' => 'Rua das Flores, 456 - Nova Porto Velho',
                'telefone' => '(69) 3214-5683',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7400,
                'lng' => -63.8800,
                'tipo' => 'UBS',
                'bairro' => 'Nova Porto Velho',
                'especialidades' => ['Clínica Geral', 'Pediatria', 'Ginecologia'],
                'servicos' => ['Consultas', 'Vacinação', 'Exames']
            ],
            [
                'id' => 7,
                'nome' => 'UBS Cidade Nova',
                'endereco' => 'Av. Jatuarana, 789 - Cidade Nova',
                'telefone' => '(69) 3214-5684',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7700,
                'lng' => -63.9100,
                'tipo' => 'UBS',
                'bairro' => 'Cidade Nova',
                'especialidades' => ['Clínica Geral', 'Odontologia'],
                'servicos' => ['Consultas', 'Vacinação', 'Odontologia']
            ],
            [
                'id' => 8,
                'nome' => 'UPA Zona Norte',
                'endereco' => 'Rua dos Ipês, 321 - Zona Norte',
                'telefone' => '(69) 3214-5685',
                'horario' => '24h',
                'lat' => -8.7800,
                'lng' => -63.9200,
                'tipo' => 'UPA',
                'bairro' => 'Zona Norte',
                'especialidades' => ['Emergência', 'Clínica Geral'],
                'servicos' => ['Atendimento 24h', 'Exames', 'Medicamentos']
            ]
        ];

        if ($bairro) {
            return array_filter($postos, function($posto) use ($bairro) {
                return stripos($posto['bairro'], $bairro) !== false;
            });
        }

        return $postos;
    }

    /**
     * Dados estáticos para unidades próximas
     */
    private function getDadosEstaticosProximos($latitude, $longitude, $raio)
    {
        $postos = $this->getDadosEstaticos();
        $proximos = [];

        foreach ($postos as $posto) {
            $distancia = $this->calcularDistancia(
                $latitude, $longitude,
                $posto['lat'], $posto['lng']
            );

            if ($distancia <= $raio) {
                $posto['distancia_km'] = round($distancia, 2);
                $proximos[] = $posto;
            }
        }

        // Ordena por distância
        usort($proximos, function($a, $b) {
            return $a['distancia_km'] <=> $b['distancia_km'];
        });

        return $proximos;
    }

    /**
     * Calcula distância entre duas coordenadas (Haversine)
     */
    private function calcularDistancia($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // raio da Terra em km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }
}


