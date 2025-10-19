# Sistema de Segurança de Rotas - Newva

## Visão Geral
Este documento descreve o sistema de segurança implementado no projeto Newva, incluindo autenticação, autorização baseada em roles e permissões.

## Middlewares Implementados

### 1. CheckRole
- **Alias**: `role`
- **Uso**: `Route::middleware(['role:administrator'])`
- **Função**: Verifica se o usuário possui uma role específica

### 2. CheckPermission
- **Alias**: `permission`
- **Uso**: `Route::middleware(['permission:users.create'])`
- **Função**: Verifica se o usuário possui uma permissão específica

### 3. EnsureUserHasRole
- **Alias**: `has.role`
- **Uso**: `Route::middleware(['has.role:medico,administrator'])`
- **Função**: Verifica se o usuário possui pelo menos uma das roles especificadas

### 4. EnsureUserHasAnyRole
- **Alias**: `has.any.role`
- **Uso**: `Route::middleware(['has.any.role'])`
- **Função**: Verifica se o usuário possui pelo menos uma role (qualquer uma)

## Roles e Permissões

### Roles Disponíveis

#### 1. Administrator
- **Acesso**: Total ao sistema
- **Permissões**: Todas as permissões do sistema
- **Funcionalidades**:
  - Gerenciar usuários
  - Gerenciar consultas
  - Gerenciar carteira de vacina
  - Gerenciar postos de saúde
  - Acesso ao painel administrativo

#### 2. Médico
- **Acesso**: Consultas e visualização
- **Permissões**:
  - `consultas.create`, `consultas.read`, `consultas.update`, `consultas.delete`
  - `carteira-vacina.read`
  - `postos-saude.read`
- **Funcionalidades**:
  - Criar, editar e deletar consultas
  - Visualizar carteira de vacina
  - Visualizar postos de saúde

#### 3. Enfermeiro
- **Acesso**: Carteira de vacina e visualização
- **Permissões**:
  - `consultas.read`
  - `carteira-vacina.create`, `carteira-vacina.read`, `carteira-vacina.update`
  - `postos-saude.read`
- **Funcionalidades**:
  - Visualizar consultas
  - Gerenciar carteira de vacina
  - Visualizar postos de saúde

#### 4. Usuário
- **Acesso**: Funcionalidades básicas
- **Permissões**:
  - `consultas.create`, `consultas.read`, `consultas.update`
  - `carteira-vacina.create`, `carteira-vacina.read`
  - `postos-saude.read`
- **Funcionalidades**:
  - Gerenciar próprias consultas
  - Visualizar e adicionar à própria carteira de vacina
  - Visualizar postos de saúde

## Estrutura de Rotas

### Rotas Públicas
- `/` - Redireciona para login

### Rotas de Autenticação
- Todas as rotas em `routes/auth.php`

### Rotas Protegidas (Requerem autenticação + role)
Todas as rotas abaixo requerem: `auth`, `verified`, `has.any.role`

#### Dashboard
- `GET /dashboard` - Dashboard principal (todos os usuários autenticados)

#### Perfil
- `GET /profile` - Visualizar perfil
- `GET /profile/edit` - Editar perfil
- `PATCH /profile` - Atualizar perfil
- `DELETE /profile` - Deletar perfil

#### Consultas
- `GET /consultas` - Listar consultas (todos)
- `GET /consultas/{id}` - Visualizar consulta (todos)
- `GET /consultas/create` - Criar consulta (médico, admin)
- `POST /consultas` - Salvar consulta (médico, admin)
- `GET /consultas/{id}/edit` - Editar consulta (médico, admin)
- `PUT /consultas/{id}` - Atualizar consulta (médico, admin)
- `DELETE /consultas/{id}` - Deletar consulta (médico, admin)

#### Carteira de Vacina
- `GET /carteira-vacina` - Listar vacinas (todos)
- `GET /carteira-vacina/{id}` - Visualizar vacina (todos)
- `GET /carteira-vacina/create` - Criar registro (enfermeiro, admin)
- `POST /carteira-vacina` - Salvar registro (enfermeiro, admin)
- `GET /carteira-vacina/{id}/edit` - Editar registro (enfermeiro, admin)
- `PUT /carteira-vacina/{id}` - Atualizar registro (enfermeiro, admin)
- `DELETE /carteira-vacina/{id}` - Deletar registro (enfermeiro, admin)

#### Postos de Saúde
- `GET /postos-saude` - Listar postos (todos)
- `GET /postos-saude/{id}` - Visualizar posto (todos)
- `GET /postos-saude/create` - Criar posto (admin)
- `POST /postos-saude` - Salvar posto (admin)
- `GET /postos-saude/{id}/edit` - Editar posto (admin)
- `PUT /postos-saude/{id}` - Atualizar posto (admin)
- `DELETE /postos-saude/{id}` - Deletar posto (admin)

### Rotas Administrativas (Apenas Administradores)
Requerem: `auth`, `role:administrator`

#### Gerenciamento de Usuários
- `GET /admin/users` - Listar usuários
- `GET /admin/users/create` - Criar usuário
- `POST /admin/users` - Salvar usuário
- `GET /admin/users/{id}` - Visualizar usuário
- `GET /admin/users/{id}/edit` - Editar usuário
- `PUT /admin/users/{id}` - Atualizar usuário
- `DELETE /admin/users/{id}` - Deletar usuário

#### Dashboard Administrativo
- `GET /admin` - Dashboard administrativo

## Como Executar os Seeders

```bash
# Executar todos os seeders
php artisan db:seed

# Executar seeders específicos
php artisan db:seed --class=LaratrustSeeder
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=VacinaSeeder
```

## Segurança Implementada

1. **Autenticação Obrigatória**: Todas as rotas protegidas requerem usuário autenticado
2. **Verificação de Email**: Rotas principais requerem email verificado
3. **Controle de Roles**: Usuários devem ter pelo menos uma role atribuída
4. **Autorização Granular**: Controle fino de permissões por funcionalidade
5. **Middleware Customizado**: Middlewares específicos para diferentes tipos de verificação
6. **Proteção de Rotas**: Estrutura organizada com diferentes níveis de acesso

## Middlewares de Segurança Adicionais

### 5. RedirectIfAuthenticated
- **Alias**: `guest`
- **Uso**: `Route::middleware(['guest'])`
- **Função**: Redireciona usuários já autenticados para o dashboard

### 6. EnsureUserNotAuthenticated
- **Alias**: `not.authenticated`
- **Uso**: `Route::middleware(['not.authenticated'])`
- **Função**: Garante que apenas usuários não autenticados acessem a rota

### 7. ThrottleAuthAttempts
- **Alias**: `throttle.auth`
- **Uso**: `Route::middleware(['throttle.auth:5,1'])`
- **Função**: Limita tentativas de autenticação para prevenir ataques de força bruta

### 8. ValidateCsrfToken
- **Alias**: `csrf.validate`
- **Uso**: `Route::middleware(['csrf.validate'])`
- **Função**: Valida tokens CSRF em requisições sensíveis

### 9. CheckRouteAccess
- **Alias**: `route.access`
- **Uso**: `Route::middleware(['route.access'])`
- **Função**: Verifica acesso baseado em regras de rota configuradas

## Configurações de Segurança

### Throttling (Rate Limiting)
- **Login**: 5 tentativas por minuto
- **Registro**: 5 tentativas por minuto
- **Recuperação de senha**: 3 tentativas por minuto
- **Verificação de email**: 6 tentativas por minuto

### Política de Senhas
- **Comprimento mínimo**: 8 caracteres
- **Requer maiúsculas**: Sim
- **Requer minúsculas**: Sim
- **Requer números**: Sim
- **Requer símbolos**: Não
- **Idade máxima**: 90 dias

### Sessão
- **Timeout**: 120 minutos
- **Regenerar no login**: Sim
- **Cookies seguros**: Configurável via env
- **HttpOnly**: Sim
- **SameSite**: Lax

## Comandos de Segurança

```bash
# Testar segurança das rotas
php artisan security:test-routes

# Atribuir role a usuário
php artisan user:assign-role email@exemplo.com administrator

# Executar seeders de segurança
php artisan db:seed --class=LaratrustSeeder
php artisan db:seed --class=PermissionSeeder
```

## Tratamento de Erros

- **403 Forbidden**: Usuário não possui permissão para acessar a rota
- **419 CSRF Token Mismatch**: Token CSRF inválido ou ausente
- **429 Too Many Requests**: Muitas tentativas de acesso (rate limiting)
- **Redirecionamento para Login**: Usuário não autenticado
- **Mensagens Descritivas**: Erros com mensagens claras sobre o que está faltando

## Verificações de Segurança Implementadas

1. ✅ **Autenticação Obrigatória** - Todas as rotas protegidas
2. ✅ **Verificação de Email** - Rotas principais requerem email verificado
3. ✅ **Controle de Roles** - Usuários devem ter pelo menos uma role
4. ✅ **Autorização Granular** - Permissões específicas por funcionalidade
5. ✅ **Rate Limiting** - Proteção contra ataques de força bruta
6. ✅ **Proteção CSRF** - Validação de tokens em requisições sensíveis
7. ✅ **Redirecionamento Inteligente** - Usuários autenticados não acessam páginas de login
8. ✅ **Verificação de Acesso por Rota** - Controle fino baseado em regras configuráveis
9. ✅ **Configuração Centralizada** - Regras de segurança em arquivo de configuração
10. ✅ **Comandos de Teste** - Ferramentas para verificar a segurança do sistema
