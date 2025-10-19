<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Prefeitura de Porto Velho') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }

        .cookie-banner {
            background-color: #f3f4f6;
            border-top: 1px solid #d1d5db;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
        }

        .focus\:not-sr-only:focus {
            position: fixed;
            width: auto;
            height: auto;
            margin: 0;
            clip: auto;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
    <!-- Cookie Banner (LGPD, inspirado no site) -->
    {{-- <div id="cookie-banner" class="cookie-banner fixed bottom-0 left-0 right-0 p-4 text-sm text-center z-50">
        <p>Utilizamos cookies em acordo com a Lei Geral de Proteção de Dados (LGPD). Ao continuar, você concorda com
            estas condições.
            <button onclick="document.getElementById('cookie-banner').style.display='none'"
                class="ml-2 underline text-blue-600 hover:text-blue-500">Fechar</button>
        </p>
    </div> --}}

    <!-- Skip to Content (Acessibilidade) -->
    <a href="#main-content"
        class="sr-only focus:not-sr-only fixed top-4 left-4 bg-white text-blue-900 px-4 py-2 rounded z-50">Ir para
        conteúdo</a>

    <!-- Header -->
    <header class="bg-blue-900 text-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-xl sm:text-2xl font-bold">Newva</h1>

                <!-- Substitua por imagem se desejar: <img src="/logo.png" alt="Logo PM PV" class="h-10 mr-2"> -->
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main id="main-content" class="min-h-screen">
        @include('layouts.navigation')
        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="mb-4 text-sm">&copy; {{ date('Y') }} Prefeitura de Porto Velho - RO - SMTI. Todos os direitos
                reservados.</p>
            <p class="mt-4 text-xs">Suporte: (69) 3212-1000 | acessibilidade@portovelho.ro.gov.br</p>
        </div>
    </footer>

    <!-- Script para menu mobile -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', () => {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
