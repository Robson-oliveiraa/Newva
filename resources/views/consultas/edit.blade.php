<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Editar Consulta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('consultas.show', $consulta) }}" 
                           class="text-blue-600 hover:text-blue-800">← Voltar para Detalhes</a>
                    </div>

                    <form method="POST" action="{{ route('consultas.update', $consulta) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="especialidade" class="block text-sm font-medium text-gray-700">
                                Especialidade
                            </label>
                            <input type="text" 
                                   name="especialidade" 
                                   id="especialidade"
                                   value="{{ old('especialidade', $consulta->especialidade) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('especialidade')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="data_hora" class="block text-sm font-medium text-gray-700">
                                Data e Hora
                            </label>
                            <input type="datetime-local" 
                                   name="data_hora" 
                                   id="data_hora"
                                   value="{{ old('data_hora', \Carbon\Carbon::parse($consulta->data_hora)->format('Y-m-d\TH:i')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('data_hora')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="local" class="block text-sm font-medium text-gray-700">
                                Local
                            </label>
                            <input type="text" 
                                   name="local" 
                                   id="local"
                                   value="{{ old('local', $consulta->local) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('local')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="observacoes" class="block text-sm font-medium text-gray-700">
                                Observações
                            </label>
                            <textarea name="observacoes" 
                                      id="observacoes" 
                                      rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observacoes', $consulta->observacoes) }}</textarea>
                            @error('observacoes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('consultas.show', $consulta) }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Atualizar Consulta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
