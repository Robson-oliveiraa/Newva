<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-6" aria-label="Bem-vindo ao seu painel">Bem-vindo, {{ auth()->user()->name }}!</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Consultas -->
                        <div class="bg-blue-50 p-4 sm:p-6 rounded-lg shadow-sm border border-blue-100">
                            <h4 class="text-base sm:text-lg font-semibold text-blue-900 mb-2">Consultas</h4>
                            <p class="text-sm sm:text-base text-blue-700 mb-4">Gerencie suas consultas médicas</p>
                            <div class="space-y-3">
                                <a href="{{ route('consultas.index') }}" 
                                   class="block w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2.5 px-4 rounded-md text-center text-sm sm:text-base transition-colors">
                                    Ver Consultas
                                </a>
                                <a href="{{ route('consultas.create') }}" 
                                   class="block w-full bg-blue-800 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-md text-center text-sm sm:text-base transition-colors">
                                    Agendar Consulta
                                </a>
                            </div>
                        </div>

                        <!-- Carteira de Vacina -->
                        <div class="bg-green-50 p-4 sm:p-6 rounded-lg shadow-sm border border-green-100">
                            <h4 class="text-base sm:text-lg font-semibold text-green-900 mb-2">Carteira de Vacina</h4>
                            <p class="text-sm sm:text-base text-green-700 mb-4">Acompanhe suas vacinas</p>
                            <div class="space-y-3">
                                <a href="{{ route('carteira-vacina.index') }}" 
                                   class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-md text-center text-sm sm:text-base transition-colors">
                                    Ver Carteira
                                </a>
                                <a href="{{ route('carteira-vacina.create') }}" 
                                   class="block w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-2.5 px-4 rounded-md text-center text-sm sm:text-base transition-colors">
                                    Adicionar Vacina
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Estatísticas rápidas -->
                    <div class="mt-6 sm:mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-100">
                            <h5 class="text-sm sm:text-base font-semibold text-gray-800">Suas Consultas</h5>
                            <p class="text-xl sm:text-2xl font-bold text-blue-900">
                                {{ auth()->user()->consultas()->count() }}
                            </p>
                            <p class="text-xs sm:text-sm text-gray-600">consultas agendadas</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-100">
                            <h5 class="text-sm sm:text-base font-semibold text-gray-800">Suas Vacinas</h5>
                            <p class="text-xl sm:text-2xl font-bold text-green-900">
                                {{ auth()->user()->vacinasAplicadas()->count() }}
                            </p>
                            <p class="text-xs sm:text-sm text-gray-600">vacinas aplicadas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>