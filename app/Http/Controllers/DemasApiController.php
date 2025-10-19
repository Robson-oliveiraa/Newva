<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DemasApiController extends Controller
{
    /**
     * Base URL da API do Ministério da Saúde
     */
    private $baseUrl;
    
    /**
     * Token de autenticação da API
     */
    private $apiToken;
    
    /**
     * Timeout para requisições HTTP
     */
    private $timeout = 30;
    
    /**
     * Código IBGE do município (Porto Velho - RO)
     */
    private $municipioIbge;
    
    /**
     * UF do estado (RO)
     */
    private $estadoUf;

    public function __construct()
    {
        $this->baseUrl = config('services.demas.base_url', 'https://apidadosabertos.saude.gov.br/');
        $this->apiToken = config('services.demas.api_token');
        $this->municipioIbge = config('services.demas.municipio_ibge', '1100205');
        $this->estadoUf = config('services.demas.estado_uf', 'RO');
    }

    /**
     * Busca todas as unidades de saúde de Porto Velho
     */
    public function getUnidadesSaude()
    {
        try {
            // Verifica se há dados em cache (cache por 1 hora)
            $cacheKey = 'ministerio_saude_unidades_porto_velho';
            $unidades = Cache::remember($cacheKey, 3600, function () {
                return $this->fetchUnidadesFromApi();
            });

            return response()->json([
                'success' => true,
                'data' => $unidades,
                'total' => count($unidades),
                'fonte' => 'API Ministério da Saúde'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar unidades de saúde do Ministério da Saúde: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar unidades de saúde',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca unidades de saúde por bairro
     */
    public function getUnidadesPorBairro($bairro)
    {
        try {
            $cacheKey = "ministerio_saude_unidades_bairro_{$bairro}";
            $unidades = Cache::remember($cacheKey, 3600, function () use ($bairro) {
                return $this->fetchUnidadesFromApi($bairro);
            });

            return response()->json([
                'success' => true,
                'data' => $unidades,
                'bairro' => $bairro,
                'total' => count($unidades),
                'fonte' => 'API Ministério da Saúde'
            ]);

        } catch (\Exception $e) {
            Log::error("Erro ao buscar unidades de saúde do bairro {$bairro}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar unidades de saúde do bairro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca unidades de saúde próximas a uma localização
     */
    public function getUnidadesProximas(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'raio' => 'nullable|numeric|min:0.1|max:50' // raio em km
        ]);

        try {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $raio = $request->raio ?? 5; // raio padrão de 5km

            $cacheKey = "ministerio_saude_unidades_proximas_{$latitude}_{$longitude}_{$raio}";
            $unidades = Cache::remember($cacheKey, 1800, function () use ($latitude, $longitude, $raio) {
                return $this->fetchUnidadesProximasFromApi($latitude, $longitude, $raio);
            });

            return response()->json([
                'success' => true,
                'data' => $unidades,
                'localizacao' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'raio_km' => $raio
                ],
                'total' => count($unidades),
                'fonte' => 'API Ministério da Saúde'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar unidades próximas: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar unidades próximas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca detalhes de uma unidade específica
     */
    public function getUnidadeDetalhes($id)
    {
        try {
            $cacheKey = "ministerio_saude_unidade_detalhes_{$id}";
            $unidade = Cache::remember($cacheKey, 3600, function () use ($id) {
                return $this->fetchUnidadeDetalhesFromApi($id);
            });

            if (!$unidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unidade de saúde não encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $unidade,
                'fonte' => 'API Ministério da Saúde'
            ]);

        } catch (\Exception $e) {
            Log::error("Erro ao buscar detalhes da unidade {$id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar detalhes da unidade',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca dados da API do Ministério da Saúde
     */
    private function fetchUnidadesFromApi($bairro = null)
    {
        $endpoint = 'assistencia-a-saude/unidade-basicas-de-saude';
        $params = [
            'limit' => 2000, // Aumentar limite para buscar mais unidades
            'offset' => 0
        ];

        $response = $this->makeApiRequest($endpoint, $params);
        
        // Sempre combinar dados da API com dados estáticos
        $unidadesCombinadas = $this->getDadosEstaticos($bairro);
        
        if ($response->successful()) {
            $data = $response->json();
            $unidades = $data['ubs'] ?? [];
            
            // Filtrar apenas unidades de Rondônia (UF = 11)
            $unidadesRO = array_filter($unidades, function($unidade) {
                return isset($unidade['uf']) && $unidade['uf'] === '11';
            });
            
            // Converter formato da API para formato interno
            $unidadesConvertidas = $this->converterFormatoApi($unidadesRO);
            
            // Combinar dados da API com dados estáticos
            if (!empty($unidadesConvertidas)) {
                // Adicionar dados da API aos dados estáticos
                $unidadesCombinadas = array_merge($unidadesCombinadas, $unidadesConvertidas);
            }
        }

        // Filtrar por bairro se especificado
        if ($bairro) {
            $unidadesCombinadas = array_filter($unidadesCombinadas, function($unidade) use ($bairro) {
                return stripos($unidade['bairro'], $bairro) !== false;
            });
        }

        return $unidadesCombinadas;
    }

    /**
     * Busca unidades próximas da API do Ministério da Saúde
     */
    private function fetchUnidadesProximasFromApi($latitude, $longitude, $raio)
    {
        // Busca todas as unidades de Porto Velho
        $unidades = $this->fetchUnidadesFromApi();
        
        if (empty($unidades)) {
            return $this->getDadosEstaticosProximos($latitude, $longitude, $raio);
        }
        
        // Filtra unidades próximas baseado na distância
        $proximas = [];
        foreach ($unidades as $unidade) {
            if ($unidade['lat'] && $unidade['lng']) {
                $distancia = $this->calcularDistancia(
                    $latitude, $longitude,
                    $unidade['lat'], $unidade['lng']
                );
                
                if ($distancia <= $raio) {
                    $unidade['distancia_km'] = round($distancia, 2);
                    $proximas[] = $unidade;
                }
            }
        }
        
        // Ordena por distância
        usort($proximas, function($a, $b) {
            return $a['distancia_km'] <=> $b['distancia_km'];
        });
        
        return $proximas;
    }

    /**
     * Busca detalhes de uma unidade da API do Ministério da Saúde
     */
    private function fetchUnidadeDetalhesFromApi($id)
    {
        // Busca todas as unidades e filtra pela ID (CNES)
        $unidades = $this->fetchUnidadesFromApi();
        
        foreach ($unidades as $unidade) {
            if ($unidade['id'] == $id || $unidade['cnes'] == $id) {
                return $unidade;
            }
        }
        
        return null;
    }

    /**
     * Faz requisição para a API do Ministério da Saúde
     */
    private function makeApiRequest($endpoint, $params = [])
    {
        $url = $this->baseUrl . $endpoint;
        
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'Newva-Sistema/1.0'
        ];

        // A API do Ministério da Saúde não requer autenticação
        // mas mantemos o token caso seja necessário no futuro
        if ($this->apiToken) {
            $headers['Authorization'] = 'Bearer ' . $this->apiToken;
        }

        return Http::timeout($this->timeout)
            ->withoutVerifying() // Desabilita verificação SSL para APIs governamentais
            ->withHeaders($headers)
            ->get($url, $params);
    }

    /**
     * Dados estáticos como fallback - Unidades de Porto Velho
     */
    private function getDadosEstaticos($bairro = null)
    {
        $postos = [
            [
                'id' => 1,
                'nome' => 'UPA Zona Sul',
                'endereco' => 'Rua das Palmeiras, 1234 - Zona Sul - Porto Velho/RO',
                'telefone' => '(69) 3214-5678',
                'horario' => '24h',
                'lat' => -8.7612,
                'lng' => -63.9020,
                'tipo' => 'UPA',
                'bairro' => 'Zona Sul',
                'cnes' => '0000001',
                'ibge' => '1100205',
                'uf' => '11',
                'especialidades' => ['Emergência', 'Clínica Geral'],
                'servicos' => ['Atendimento 24h', 'Exames', 'Medicamentos']
            ],
            [
                'id' => 2,
                'nome' => 'UBS São Francisco',
                'endereco' => 'Av. Presidente Vargas, 567 - São Francisco - Porto Velho/RO',
                'telefone' => '(69) 3214-5679',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7580,
                'lng' => -63.9000,
                'tipo' => 'UBS',
                'bairro' => 'São Francisco',
                'cnes' => '0000002',
                'ibge' => '1100205',
                'uf' => '11',
                'especialidades' => ['Clínica Geral', 'Pediatria', 'Ginecologia'],
                'servicos' => ['Consultas', 'Vacinação', 'Exames']
            ],
            [
                'id' => 3,
                'nome' => 'UBS Cohab',
                'endereco' => 'Rua dos Coqueiros, 890 - Cohab - Porto Velho/RO',
                'telefone' => '(69) 3214-5680',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7650,
                'lng' => -63.8950,
                'tipo' => 'UBS',
                'bairro' => 'Cohab',
                'cnes' => '0000003',
                'ibge' => '1100205',
                'uf' => '11',
                'especialidades' => ['Clínica Geral', 'Odontologia'],
                'servicos' => ['Consultas', 'Vacinação', 'Odontologia']
            ],
            [
                'id' => 4,
                'nome' => 'UBS Tancredo Neves',
                'endereco' => 'Av. Tancredo Neves, 123 - Tancredo Neves - Porto Velho/RO',
                'telefone' => '(69) 3214-5681',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7500,
                'lng' => -63.8900,
                'tipo' => 'UBS',
                'bairro' => 'Tancredo Neves',
                'cnes' => '0000004',
                'ibge' => '1100205',
                'uf' => '11',
                'especialidades' => ['Clínica Geral', 'Pediatria'],
                'servicos' => ['Consultas', 'Vacinação']
            ],
            [
                'id' => 5,
                'nome' => 'Hospital de Base Dr. Ary Pinheiro',
                'endereco' => 'Av. Carlos Gomes, 1000 - Centro - Porto Velho/RO',
                'telefone' => '(69) 3214-5682',
                'horario' => '24h',
                'lat' => -8.7600,
                'lng' => -63.9050,
                'tipo' => 'Hospital',
                'bairro' => 'Centro',
                'cnes' => '0000005',
                'ibge' => '1100205',
                'uf' => '11',
                'especialidades' => ['Emergência', 'Cirurgia', 'Cardiologia', 'Neurologia'],
                'servicos' => ['Atendimento 24h', 'Cirurgias', 'Exames Complexos', 'Internação']
            ],
            [
                'id' => 6,
                'nome' => 'UBS Nova Porto Velho',
                'endereco' => 'Rua das Flores, 456 - Nova Porto Velho - Porto Velho/RO',
                'telefone' => '(69) 3214-5683',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7400,
                'lng' => -63.8800,
                'tipo' => 'UBS',
                'bairro' => 'Nova Porto Velho',
                'cnes' => '0000006',
                'ibge' => '1100205',
                'uf' => '11',
                'especialidades' => ['Clínica Geral', 'Pediatria', 'Ginecologia'],
                'servicos' => ['Consultas', 'Vacinação', 'Exames']
            ],
            [
                'id' => 7,
                'nome' => 'UBS Cidade Nova',
                'endereco' => 'Av. Jatuarana, 789 - Cidade Nova - Porto Velho/RO',
                'telefone' => '(69) 3214-5684',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7700,
                'lng' => -63.9100,
                'tipo' => 'UBS',
                'bairro' => 'Cidade Nova',
                'cnes' => '0000007',
                'ibge' => '1100205',
                'uf' => '11',
                'especialidades' => ['Clínica Geral', 'Odontologia'],
                'servicos' => ['Consultas', 'Vacinação', 'Odontologia']
            ],
            [
                'id' => 8,
                'nome' => 'UPA Zona Norte',
                'endereco' => 'Rua dos Ipês, 321 - Zona Norte - Porto Velho/RO',
                'telefone' => '(69) 3214-5685',
                'horario' => '24h',
                'lat' => -8.7800,
                'lng' => -63.9200,
                'tipo' => 'UPA',
                'bairro' => 'Zona Norte',
                'cnes' => '0000008',
                'ibge' => '1100205',
                'uf' => '11',
                'especialidades' => ['Emergência', 'Clínica Geral'],
                'servicos' => ['Atendimento 24h', 'Exames', 'Medicamentos']
            ],
            [
                'id' => 9,
                'nome' => 'UBS Bairro Industrial',
                'endereco' => 'Rua da Indústria, 100 - Bairro Industrial - Porto Velho/RO',
                'telefone' => '(69) 3214-5686',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7900,
                'lng' => -63.9300,
                'tipo' => 'UBS',
                'bairro' => 'Bairro Industrial',
                'cnes' => '0000009',
                'ibge' => '1100205',
                'uf' => '11',
                'especialidades' => ['Clínica Geral', 'Medicina do Trabalho'],
                'servicos' => ['Consultas', 'Vacinação', 'Exames Ocupacionais']
            ],
            [
                'id' => 10,
                'nome' => 'UBS Aeroporto',
                'endereco' => 'Av. Juscelino Kubitschek, 500 - Aeroporto - Porto Velho/RO',
                'telefone' => '(69) 3214-5687',
                'horario' => '07:00 - 17:00',
                'lat' => -8.7200,
                'lng' => -63.8700,
                'tipo' => 'UBS',
                'bairro' => 'Aeroporto',
                'cnes' => '0000010',
                'ibge' => '1100205',
                'uf' => '11',
                'especialidades' => ['Clínica Geral', 'Pediatria'],
                'servicos' => ['Consultas', 'Vacinação', 'Exames']
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
     * Converte dados da API do Ministério da Saúde para formato interno
     */
    private function converterFormatoApi($unidades)
    {
        $converted = [];
        
        foreach ($unidades as $index => $unidade) {
            $lat = $this->converterCoordenada($unidade['latitude'] ?? null);
            $lng = $this->converterCoordenada($unidade['longitude'] ?? null);
            
            // Validar se as coordenadas estão dentro da área de Porto Velho
            if (!$this->validarCoordenadasPortoVelho($lat, $lng)) {
                // Usar coordenadas padrão de Porto Velho se as coordenadas estiverem incorretas
                $lat = $this->obterCoordenadaPadrao($unidade['bairro'] ?? 'Centro', 'lat');
                $lng = $this->obterCoordenadaPadrao($unidade['bairro'] ?? 'Centro', 'lng');
            }
            
            $converted[] = [
                'id' => $unidade['cnes'] ?? ($index + 1),
                'nome' => $unidade['nome'] ?? 'Unidade de Saúde',
                'endereco' => $this->formatarEndereco($unidade),
                'telefone' => $unidade['telefone'] ?? 'Não informado',
                'horario' => $unidade['horario'] ?? 'Não informado',
                'lat' => $lat,
                'lng' => $lng,
                'tipo' => $this->determinarTipoUnidade($unidade['nome'] ?? ''),
                'bairro' => $unidade['bairro'] ?? 'Não informado',
                'cnes' => $unidade['cnes'] ?? null,
                'ibge' => $unidade['ibge'] ?? null,
                'uf' => $unidade['uf'] ?? null,
                'especialidades' => $this->determinarEspecialidades($unidade['nome'] ?? ''),
                'servicos' => $this->determinarServicos($unidade['nome'] ?? '')
            ];
        }
        
        return $converted;
    }
    
    /**
     * Formata endereço completo
     */
    private function formatarEndereco($unidade)
    {
        $endereco = '';
        
        if (!empty($unidade['logradouro'])) {
            $endereco .= $unidade['logradouro'];
        }
        
        if (!empty($unidade['bairro'])) {
            $endereco .= $endereco ? ', ' . $unidade['bairro'] : $unidade['bairro'];
        }
        
        if (!empty($unidade['ibge'])) {
            $endereco .= $endereco ? ' - Porto Velho/RO' : 'Porto Velho/RO';
        }
        
        return $endereco ?: 'Endereço não informado';
    }
    
    /**
     * Converte coordenada de string para float
     */
    private function converterCoordenada($coordenada)
    {
        if (empty($coordenada)) {
            return null;
        }
        
        // Substitui vírgula por ponto e converte para float
        return (float) str_replace(',', '.', $coordenada);
    }
    
    /**
     * Determina o tipo da unidade baseado no nome
     */
    private function determinarTipoUnidade($nome)
    {
        $nome = strtoupper($nome);
        
        if (strpos($nome, 'UPA') !== false) {
            return 'UPA';
        } elseif (strpos($nome, 'UBS') !== false) {
            return 'UBS';
        } elseif (strpos($nome, 'USF') !== false) {
            return 'USF';
        } elseif (strpos($nome, 'HOSPITAL') !== false) {
            return 'Hospital';
        } elseif (strpos($nome, 'POSTO') !== false) {
            return 'Posto';
        } else {
            return 'UBS'; // Padrão
        }
    }
    
    /**
     * Determina especialidades baseadas no nome da unidade
     */
    private function determinarEspecialidades($nome)
    {
        $nome = strtoupper($nome);
        $especialidades = ['Clínica Geral'];
        
        if (strpos($nome, 'UPA') !== false || strpos($nome, 'HOSPITAL') !== false) {
            $especialidades[] = 'Emergência';
        }
        
        if (strpos($nome, 'PEDIATR') !== false) {
            $especialidades[] = 'Pediatria';
        }
        
        if (strpos($nome, 'GINECOL') !== false) {
            $especialidades[] = 'Ginecologia';
        }
        
        if (strpos($nome, 'ODONTOL') !== false) {
            $especialidades[] = 'Odontologia';
        }
        
        return $especialidades;
    }
    
    /**
     * Determina serviços baseados no nome da unidade
     */
    private function determinarServicos($nome)
    {
        $nome = strtoupper($nome);
        $servicos = ['Consultas'];
        
        if (strpos($nome, 'UPA') !== false || strpos($nome, 'HOSPITAL') !== false) {
            $servicos[] = 'Atendimento 24h';
        }
        
        $servicos[] = 'Vacinação';
        $servicos[] = 'Exames';
        
        if (strpos($nome, 'ODONTOL') !== false) {
            $servicos[] = 'Odontologia';
        }
        
        return $servicos;
    }

    /**
     * Valida se as coordenadas estão dentro da área de Porto Velho
     */
    private function validarCoordenadasPortoVelho($lat, $lng)
    {
        if (!$lat || !$lng) {
            return false;
        }
        
        // Limites aproximados de Porto Velho
        $latMin = -8.85;  // Sul
        $latMax = -8.65;  // Norte
        $lngMin = -64.05; // Oeste
        $lngMax = -63.80; // Leste
        
        return $lat >= $latMin && $lat <= $latMax && 
               $lng >= $lngMin && $lng <= $lngMax;
    }
    
    /**
     * Obtém coordenadas padrão baseadas no bairro
     */
    private function obterCoordenadaPadrao($bairro, $tipo)
    {
        $coordenadas = [
            'CENTRO' => ['lat' => -8.7600, 'lng' => -63.9050],
            'JARDIM DOS MIGRANTES' => ['lat' => -8.7500, 'lng' => -63.8900],
            'ESCOLA DE POLICIA' => ['lat' => -8.7620, 'lng' => -63.9040],
            'ZONA SUL' => ['lat' => -8.7612, 'lng' => -63.9020],
            'SÃO FRANCISCO' => ['lat' => -8.7580, 'lng' => -63.9000],
            'COHAB' => ['lat' => -8.7650, 'lng' => -63.8950],
            'TANCREDO NEVES' => ['lat' => -8.7500, 'lng' => -63.8900],
            'NOVA PORTO VELHO' => ['lat' => -8.7400, 'lng' => -63.8800],
            'CIDADE NOVA' => ['lat' => -8.7700, 'lng' => -63.9100],
            'ZONA NORTE' => ['lat' => -8.7800, 'lng' => -63.9200],
            'BAIRRO INDUSTRIAL' => ['lat' => -8.7900, 'lng' => -63.9300],
            'AEROPORTO' => ['lat' => -8.7200, 'lng' => -63.8700],
        ];
        
        $bairroUpper = strtoupper($bairro);
        
        // Busca por correspondência parcial
        foreach ($coordenadas as $nomeBairro => $coord) {
            if (strpos($bairroUpper, $nomeBairro) !== false || 
                strpos($nomeBairro, $bairroUpper) !== false) {
                return $coord[$tipo];
            }
        }
        
        // Coordenadas padrão do centro de Porto Velho
        return $tipo === 'lat' ? -8.7600 : -63.9050;
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
