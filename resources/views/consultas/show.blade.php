<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-blue-900">
            {{ __('Detalhes da Consulta') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('consultas.index') }}"
                            class="text-blue-600 hover:text-blue-800 text-sm sm:text-base font-medium transition-colors">
                            ← Voltar para Consultas
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">
                                Informações da Consulta
                            </h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Especialidade:</dt>
                                    <dd class="text-sm sm:text-base text-gray-900">{{ $consulta->especialidade }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Data e Hora:</dt>
                                    <dd class="text-sm sm:text-base text-gray-900">
                                        {{ \Carbon\Carbon::parse($consulta->data_hora)->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Local:</dt>
                                    <dd class="text-sm sm:text-base text-gray-900">{{ $consulta->local }}</dd>
                                </div>
                                @if ($consulta->observacoes)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-600">Observações:</dt>
                                        <dd class="text-sm sm:text-base text-gray-900">{{ $consulta->observacoes }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">
                                Ações
                            </h3>
                            <div class="space-y-3">
                                <a href="{{ route('consultas.edit', $consulta) }}"
                                    class="block w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2.5 px-4 rounded-md text-center text-sm sm:text-base transition-colors">
                                    Editar Consulta
                                </a>
                                <form action="{{ route('consultas.destroy', $consulta) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="block w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-md text-sm sm:text-base transition-colors"
                                        onclick="return confirm('Tem certeza que deseja cancelar esta consulta?')"
                                        aria-label="Cancelar consulta">
                                        Cancelar Consulta
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
