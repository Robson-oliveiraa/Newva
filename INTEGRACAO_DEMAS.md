# Integração com API da DEMAS - Newva

## Visão Geral

Este documento descreve a integração implementada para buscar localizações das unidades de saúde de Porto Velho através da API da DEMAS.

## Funcionalidades Implementadas

### 1. Controller DemasApiController

**Localização**: `app/Http/Controllers/DemasApiController.php`

**Funcionalidades**:
- ✅ Busca todas as unidades de saúde
- ✅ Busca unidades por bairro
- ✅ Busca unidades próximas a uma localização
- ✅ Busca detalhes de uma unidade específica
- ✅ Sistema de cache para otimizar performance
- ✅ Fallback para dados estáticos em caso de erro

### 2. Configurações

**Arquivo**: `config/services.php`

```php
'demas' => [
    'base_url' => env('DEMAS_API_URL', 'https://api.demas.portovelho.ro.gov.br'),
    'api_token' => env('DEMAS_API_TOKEN'),
    'timeout' => env('DEMAS_API_TIMEOUT', 30),
    'cache_ttl' => env('DEMAS_CACHE_TTL', 3600),
],
```

### 3. Variáveis de Ambiente

Adicione ao arquivo `.env`:

```env
# Configurações da API da DEMAS
DEMAS_API_URL=https://api.demas.portovelho.ro.gov.br
DEMAS_API_TOKEN=seu_token_aqui
DEMAS_API_TIMEOUT=30
DEMAS_CACHE_TTL=3600
```

## Endpoints da API

### 1. Buscar Todas as Unidades
```
GET /api/demas/unidades-saude
```

**Resposta**:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nome": "UPA Zona Sul",
            "endereco": "Rua das Palmeiras, 1234 - Zona Sul",
            "telefone": "(69) 3214-5678",
            "horario": "24h",
            "lat": -8.7612,
            "lng": -63.9020,
            "tipo": "UPA",
            "bairro": "Zona Sul",
            "especialidades": ["Emergência", "Clínica Geral"],
            "servicos": ["Atendimento 24h", "Exames", "Medicamentos"]
        }
    ],
    "total": 8
}
```

### 2. Buscar por Bairro
```
GET /api/demas/unidades-saude/bairro/{bairro}
```

**Exemplo**: `/api/demas/unidades-saude/bairro/Centro`

### 3. Buscar Unidades Próximas
```
GET /api/demas/unidades-saude/proximas?latitude=-8.7612&longitude=-63.9020&raio=5
```

**Parâmetros**:
- `latitude` (obrigatório): Latitude da localização
- `longitude` (obrigatório): Longitude da localização
- `raio` (opcional): Raio de busca em km (padrão: 5)

### 4. Detalhes de uma Unidade
```
GET /api/demas/unidades-saude/{id}
```

## Integração com PostoSaudeController

O `PostoSaudeController` foi atualizado para usar a API da DEMAS:

### Métodos Atualizados

1. **`index()`**: Busca todas as unidades via API
2. **`porBairro($bairro)`**: Busca unidades por bairro
3. **`proximos(Request $request)`**: Busca unidades próximas
4. **`show($id)`**: Mostra detalhes de uma unidade

### Sistema de Fallback

Se a API da DEMAS não estiver disponível, o sistema automaticamente usa dados estáticos como fallback, garantindo que a aplicação continue funcionando.

## Rotas Disponíveis

### Rotas Web (Protegidas)
- `GET /postos-saude` - Lista todos os postos
- `GET /postos-saude/bairro/{bairro}` - Postos por bairro
- `GET /postos-saude/proximos` - Postos próximos
- `GET /postos-saude/{id}` - Detalhes de um posto

### Rotas API (Públicas)
- `GET /api/demas/unidades-saude` - API para todas as unidades
- `GET /api/demas/unidades-saude/bairro/{bairro}` - API por bairro
- `GET /api/demas/unidades-saude/proximas` - API unidades próximas
- `GET /api/demas/unidades-saude/{id}` - API detalhes da unidade

## Cache

O sistema implementa cache automático para otimizar performance:

- **Cache de unidades**: 1 hora (3600 segundos)
- **Cache de proximidade**: 30 minutos (1800 segundos)
- **Cache de detalhes**: 1 hora (3600 segundos)

## Tratamento de Erros

- ✅ Logs de erro detalhados
- ✅ Fallback para dados estáticos
- ✅ Respostas JSON padronizadas
- ✅ Códigos de status HTTP apropriados

## Dados Estáticos de Fallback

Inclui 8 unidades de saúde principais de Porto Velho:

1. **UPA Zona Sul** - 24h
2. **UBS São Francisco** - 07:00-17:00
3. **UBS Cohab** - 07:00-17:00
4. **UBS Tancredo Neves** - 07:00-17:00
5. **Hospital de Base Dr. Ary Pinheiro** - 24h
6. **UBS Nova Porto Velho** - 07:00-17:00
7. **UBS Cidade Nova** - 07:00-17:00
8. **UPA Zona Norte** - 24h

## Próximos Passos

1. **Configurar URL real da API da DEMAS**
2. **Obter token de autenticação (se necessário)**
3. **Testar integração com API real**
4. **Ajustar mapeamento de dados conforme necessário**
5. **Implementar monitoramento de saúde da API**

## Exemplo de Uso

```php
// Buscar todas as unidades
$response = Http::get('/api/demas/unidades-saude');
$unidades = $response->json()['data'];

// Buscar unidades próximas
$response = Http::get('/api/demas/unidades-saude/proximas', [
    'latitude' => -8.7612,
    'longitude' => -63.9020,
    'raio' => 5
]);
$proximas = $response->json()['data'];
```

## Suporte

Para dúvidas ou problemas com a integração, consulte:
- Logs da aplicação em `storage/logs/laravel.log`
- Documentação da API da DEMAS
- Equipe de desenvolvimento

