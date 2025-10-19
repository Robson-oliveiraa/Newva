<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Login') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Assumindo Tailwind CSS via Breeze; adicione se necessário -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }

        .bg-hero {
            background-image: url('https://via.placeholder.com/800x600?text=Banner+Oficial+Porto+Velho');
            background-size: cover;
            background-position: center;
        }

        .cookie-banner {
            background-color: #f3f4f6;
            border-top: 1px solid #d1d5db;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <!-- Cookie Banner (inspirado no LGPD do site) -->
    <div id="cookie-banner" class="cookie-banner fixed bottom-0 left-0 right-0 p-4 text-sm text-center z-50">
        <p>Utilizamos cookies em acordo com a Lei Geral de Proteção de Dados (LGPD). Ao continuar, você concorda com
            estas condições.
            <button onclick="document.getElementById('cookie-banner').style.display='none'"
                class="ml-2 underline text-blue-600">&times; Fechar</button>
        </p>
    </div>

    <!-- Header (inspirado no cabeçalho oficial: logo + menu simples) -->
    <header class="bg-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-2xl font-bold">Newva</h1>
                <!-- Logo pode ser uma imagem: <img src="logo.png" alt="Logo PM PV" class="h-10 mr-2"> -->
            </div>
        </div>
    </header>

    <!-- Main Content: Layout em duas colunas, login na direita -->
    <main class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <!-- Coluna Esquerda: Introdução (inspirado na seção principal do site, com foco em serviços públicos) -->
            <div
                class="bg-gradient-to-r from-green-600 via-green-700 to-green-800 rounded-2xl p-10 text-white flex flex-col justify-center shadow-2xl relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm rounded-2xl"></div>

                <div class="relative z-10 space-y-6">
                    <h2 class="text-4xl font-extrabold drop-shadow-lg">
                        Bem-vindo ao <span class="text-yellow-300">Newva</span>
                    </h2>

                    <p class="text-lg text-gray-100 leading-relaxed max-w-3xl">
                        Cuide da sua saúde com praticidade. Aqui você pode consultar informações sobre vacinação,
                        agendar consultas nos postos de saúde e acompanhar seu histórico de atendimentos de forma
                        simples e segura.
                    </p>

                    <ul class="list-disc list-inside space-y-2 text-gray-100">
                        <li>Agendamento de consultas médicas</li>
                        <li>Carteira de vacinação digital</li>
                        <li>Notificações de vacinas vencidas ou próximas</li>
                        <li>Localização dos postos de saúde mais próximos</li>
                    </ul>
                </div>
            </div>




            <!-- Coluna Direita: Formulário de Login (alinhado à direita da tela em desktop) -->
            <div class="max-w-md mx-auto lg:ml-auto">
                <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4 text-sm text-green-600" :status="session('status')" />


                    <form method="POST" action="{{ route('login') }}"
                        class="space-y-6 sm:space-y-5 w-full max-w-lg mx-auto">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Email')"
                                class="block text-sm  font-medium text-gray-700 mb-1.5 sm:text-base" />
                            <x-text-input id="email"
                                class="block p-1 mt-1 w-full border border-black rounded-md  shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base py-2.5 sm:py-3"
                                type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username" aria-describedby="email-error" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs sm:text-sm text-red-600"
                                id="email-error" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Senha')"
                                class="block text-sm font-medium text-gray-700 mb-1.5 sm:text-base" />
                            <x-text-input id="password"
                                class="block p-1 mt-1 w-full border border-black rounded-md  shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base py-2.5 sm:py-3"
                                type="password" name="password" required autocomplete="current-password"
                                aria-describedby="password-error" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs sm:text-sm text-red-600"
                                id="password-error" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center">
                                <input id="remember_me" type="checkbox"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 h-4 w-4 sm:h-5 sm:w-5"
                                    name="remember" aria-label="{{ __('Lembrar de mim') }}" />
                                <span class="ms-2 text-xs sm:text-sm text-gray-600">{{ __('Lembrar de mim') }}</span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-x-6">
                            @if (Route::has('password.request'))
                                <a class="underline text-xs sm:text-sm text-blue-600 hover:text-blue-500 transition-colors"
                                    href="{{ route('password.request') }}" aria-label="{{ __('Esqueceu sua senha?') }}">
                                    {{ __('Esqueceu sua senha?') }}
                                </a>
                            @endif

                            <x-primary-button
                                class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-2.5 sm:py-3 rounded-md font-semibold text-sm sm:text-base w-full sm:w-auto">
                                {{ __('Entrar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer (inspirado no rodapé oficial: direitos + links) -->
    <footer class="bg-blue-900 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-4">&copy; 2025 Prefeitura de Porto Velho - RO - SMTI. Todos os direitos reservados.</p>
            <p class="mt-4 text-xs">Suporte: (69) 3212-1000 | acessibilidade@portovelho.ro.gov.br</p>
        </div>
    </footer>

    <!-- Scripts para acessibilidade (inspirado no VLibras do site) -->
    <script>
        // Exemplo simples para VLibras ou acessibilidade; integre o widget oficial se necessário
        document.addEventListener('DOMContentLoaded', function() {
            // Skip to content
            const skipLink = document.createElement('a');
            skipLink.href = '#main-content';
            skipLink.textContent = 'Ir para conteúdo';
            skipLink.className =
                'sr-only focus:not-sr-only fixed top-4 left-4 bg-white text-blue-900 px-4 py-2 rounded z-50';
            document.body.prepend(skipLink);
        });
    </script>
</body>

</html>
