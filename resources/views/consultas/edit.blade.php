<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-blue-900">
            {{ __('Editar Consulta') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('consultas.show', $consulta) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm sm:text-base font-medium transition-colors">
                            ← Voltar para Detalhes
                        </a>
                    </div>

                    <form method="POST" action="{{ route('consultas.update', $consulta) }}" class="space-y-6 sm:space-y-5 max-w-lg mx-auto">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="especialidade" class="block text-sm sm:text-base font-medium text-gray-700 mb-1.5">
                                Especialidade
                            </label>
                            <input type="text" 
                                   name="especialidade" 
                                   id="especialidade"
                                   value="{{ old('especialidade', $consulta->especialidade) }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base py-2.5 sm:py-3"
                                   required
                                   aria-describedby="especialidade-error">
                            @error('especialidade')
                                <p class="mt-1 text-xs sm:text-sm text-red-600" id="especialidade-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="data_hora" class="block text-sm sm:text-base font-medium text-gray-700 mb-1.5">
                                Data e Hora
                            </label>
                            <input type="datetime-local" 
                                   name="data_hora" 
                                   id="data_hora"
                                   value="{{ old('data_hora', \Carbon\Carbon::parse($consulta->data_hora)->format('Y-m-d\TH:i')) }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base py-2.5 sm:py-3"
                                   required
                                   aria-describedby="data_hora-error">
                            @error('data_hora')
                                <p class="mt-1 text-xs sm:text-sm text-red-600" id="data_hora-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="local" class="block text-sm sm:text-base font-medium text-gray-700 mb-1.5">
                                Local
                            </label>
                            <input type="text" 
                                   name="local" 
                                   id="local"
                                   value="{{ old('local', $consulta->local) }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base py-2.5 sm:py-3"
                                   required
                                   aria-describedby="local-error">
                            @error('local')
                                <p class="mt-1 text-xs sm:text-sm text-red-600" id="local-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="observacoes" class="block text-sm sm:text-base font-medium text-gray-700 mb-1.5">
                                Observações
                            </label>
                            <textarea name="observacoes" 
                                      id="observacoes" 
                                      rows="4"
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base py-2.5 sm:py-3">{{ old('observacoes', $consulta->observacoes) }}</textarea>
                            @error('observacoes')
                                <p class="mt-1 text-xs sm:text-sm text-red-600" id="observacoes-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-4 sm:gap-x-6">
                            <a href="{{ route('consultas.show', $consulta) }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2.5 px-4 rounded-md text-sm sm:text-base text-center transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2.5 px-4 rounded-md text-sm sm:text-base transition-colors">
                                Atualizar Consulta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>