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
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. ADICIONAR VALIDAÇÃO PARA OS NOVOS CAMPOS: cpf, sexo, idade
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
            // Novos Campos
            'cpf' => ['required', 'string', 'max:14', 'unique:'.User::class], // O CPF é único
            'sexo' => ['required', 'string', 'in:M,F,Outro'], // Tipos ENUM permitidos
            'idade' => ['required', 'integer', 'min:1', 'max:150'],
        ]);

        // 2. ADICIONAR OS NOVOS CAMPOS NA CRIAÇÃO DO USUÁRIO
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

            // Novos Campos
            'cpf' => $request->cpf,
            'sexo' => $request->sexo,
            'idade' => $request->idade,
        ]);

        $user->addRole("usuario"); // Adiciona o papel "usuario" via Laratrust

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
