<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * Apenas para administradores.
     */
    public function create(): View
    {
        // Verificar se o usuário é administrador
        if (!auth()->user()->hasRole('administrator')) {
            abort(403, 'Acesso negado. Apenas administradores podem registrar novos usuários.');
        }

        return view('admin.users.create');
    }

    /**
     * Handle an incoming registration request.
     * Apenas para administradores.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Verificar se o usuário é administrador
        if (!auth()->user()->hasRole('administrator')) {
            abort(403, 'Acesso negado. Apenas administradores podem registrar novos usuários.');
        }

        // Validação dos dados
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cpf' => ['required', 'string', 'max:14', 'unique:'.User::class],
            'sexo' => ['required', 'string', 'in:M,F,Outro'],
            'idade' => ['required', 'integer', 'min:1', 'max:150'],
            'role' => ['required', 'string', 'in:usuario,medico,enfermeiro,administrator'],
        ]);

        // Criar o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $request->cpf,
            'sexo' => $request->sexo,
            'idade' => $request->idade,
        ]);

        // Atribuir a role especificada pelo administrador
        $user->addRole($request->role);

        event(new Registered($user));

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }
}
