<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-6">Bem-vindo, {{ auth()->user()->name }}!</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Consultas -->
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-blue-800 mb-2">Consultas</h4>
                            <p class="text-blue-600 mb-4">Gerencie suas consultas médicas</p>
                            <div class="space-y-2">
                                <a href="{{ route('consultas.index') }}" 
                                   class="block w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                                    Ver Consultas
                                </a>
                                <a href="{{ route('consultas.create') }}" 
                                   class="block w-full bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded text-center">
                                    Agendar Consulta
                                </a>
                            </div>
                        </div>

                        <!-- Carteira de Vacina -->
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-green-800 mb-2">Carteira de Vacina</h4>
                            <p class="text-green-600 mb-4">Acompanhe suas vacinas</p>
                            <div class="space-y-2">
                                <a href="{{ route('carteira-vacina.index') }}" 
                                   class="block w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center">
                                    Ver Carteira
                                </a>
                                <a href="{{ route('carteira-vacina.create') }}" 
                                   class="block w-full bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded text-center">
                                    Adicionar Vacina
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Estatísticas rápidas -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h5 class="font-semibold text-gray-800">Suas Consultas</h5>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ auth()->user()->consultas()->count() }}
                            </p>
                            <p class="text-sm text-gray-600">consultas agendadas</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h5 class="font-semibold text-gray-800">Suas Vacinas</h5>
                            <p class="text-2xl font-bold text-green-600">
                                {{ auth()->user()->vacinasAplicadas()->count() }}
                            </p>
                            <p class="text-sm text-gray-600">vacinas aplicadas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
