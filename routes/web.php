<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\CarteiraVacinaController;
use App\Http\Controllers\PostoSaudeController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\VacinaApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemasApiController;
use Illuminate\Support\Facades\Route;

// ========================================
// ROTAS PÚBLICAS
// ========================================

// Redireciona a raiz para o login
Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

// ========================================
// ROTAS DE AUTENTICAÇÃO
// ========================================
require __DIR__.'/auth.php';

// ========================================
// ROTAS PROTEGIDAS - AUTENTICAÇÃO OBRIGATÓRIA
// ========================================
Route::middleware(['auth', 'verified', 'has.any.role', 'route.access'])->group(function () {
    
    // Dashboard - Acesso para todos os usuários autenticados
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ========================================
    // PERFIL DO USUÁRIO - Acesso para todos os usuários autenticados
    // ========================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    // ========================================
    // CONSULTAS - Acesso baseado em roles
    // ========================================
    Route::prefix('consultas')->name('consultas.')->group(function () {
        // Médicos e Administradores podem criar, editar e deletar
        Route::middleware(['has.role:medico,administrator'])->group(function () {
            Route::get('/create', [ConsultaController::class, 'create'])->name('create');
            Route::post('/', [ConsultaController::class, 'store'])->name('store');
            Route::get('/{consulta}/edit', [ConsultaController::class, 'edit'])->name('edit');
            Route::put('/{consulta}', [ConsultaController::class, 'update'])->name('update');
            Route::delete('/{consulta}', [ConsultaController::class, 'destroy'])->name('destroy');
        });
        
        // Todos os usuários autenticados podem listar e visualizar
        Route::get('/', [ConsultaController::class, 'index'])->name('index');
        Route::get('/{consulta}', [ConsultaController::class, 'show'])->name('show');
    });
    
    // ========================================
    // CARTEIRA DE VACINA - Acesso baseado em roles
    // ========================================
    Route::prefix('carteira-vacina')->name('carteira-vacina.')->group(function () {
        // Médicos, Enfermeiros e Administradores podem criar, editar e deletar
        Route::middleware(['has.role:medico,enfermeiro,administrator'])->group(function () {
            Route::get('/create', [VacinaApplicationController::class, 'create'])->name('create');
            Route::post('/', [VacinaApplicationController::class, 'store'])->name('store');
            Route::get('/{carteiraVacina}/edit', [VacinaApplicationController::class, 'edit'])->name('edit');
            Route::put('/{carteiraVacina}', [VacinaApplicationController::class, 'update'])->name('update');
            Route::delete('/{carteiraVacina}', [VacinaApplicationController::class, 'destroy'])->name('destroy');
        });
        
        // Todos os usuários autenticados podem listar e visualizar
        Route::get('/', [VacinaApplicationController::class, 'index'])->name('index');
        Route::get('/{carteiraVacina}', [VacinaApplicationController::class, 'show'])->name('show');
    });
    
    // ========================================
    // POSTOS DE SAÚDE - Acesso para todos os usuários autenticados
    // ========================================
    Route::prefix('postos-saude')->name('postos-saude.')->group(function () {
        Route::get('/', [PostoSaudeController::class, 'index'])->name('index');
        
        // Apenas administradores podem gerenciar postos
        Route::middleware(['role:administrator'])->group(function () {
            Route::get('/create', [PostoSaudeController::class, 'create'])->name('create');
            Route::post('/', [PostoSaudeController::class, 'store'])->name('store');
            Route::get('/{postoSaude}/edit', [PostoSaudeController::class, 'edit'])->name('edit');
            Route::put('/{postoSaude}', [PostoSaudeController::class, 'update'])->name('update');
            Route::delete('/{postoSaude}', [PostoSaudeController::class, 'destroy'])->name('destroy');
        });
        
        Route::get('/{postoSaude}', [PostoSaudeController::class, 'show'])->name('show');
    });
});

// ========================================
// ROTAS ADMINISTRATIVAS - Apenas para Administradores
// ========================================
Route::middleware(['auth', 'role:administrator'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard administrativo
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        // Gerenciamento de usuários
        Route::resource('users', AdminController::class);
        
        // Gerenciamento de médicos
        Route::resource('medicos', MedicoController::class);
        
        // Dashboard DEMAS
        Route::get('demas', [DemasApiController::class, 'dashboard'])->name('demas.dashboard');
        Route::get('demas/unidades', [DemasApiController::class, 'getUnidadesPortoVelho'])->name('demas.unidades');
        Route::get('demas/vacinas', [DemasApiController::class, 'getVacinasDistribuidas'])->name('demas.vacinas');
        Route::get('demas/rondonia', [DemasApiController::class, 'getDadosRondonia'])->name('demas.rondonia');
        Route::post('demas/clear-cache', [DemasApiController::class, 'clearCache'])->name('demas.clear-cache');
        
        // Registro de novos usuários (apenas para administradores)
        Route::get('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])
            ->name('register');
        
        Route::post('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])
            ->name('register.store')
            ->middleware('throttle:5,1');
    });