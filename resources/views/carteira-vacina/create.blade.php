<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Adicionar Vacina à Carteira') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('carteira-vacina.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">
                                Paciente
                            </label>
                            <select name="user_id" 
                                    id="user_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">Selecione um paciente</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }} ({{ $usuario->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="vacina_id" class="block text-sm font-medium text-gray-700">
                                Vacina
                            </label>
                            <select name="vacina_id" 
                                    id="vacina_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">Selecione uma vacina</option>
                                @foreach($vacinas as $vacina)
                                    <option value="{{ $vacina->id }}" {{ old('vacina_id') == $vacina->id ? 'selected' : '' }}>
                                        {{ $vacina->nome }} - {{ $vacina->descricao }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vacina_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="data_aplicacao" class="block text-sm font-medium text-gray-700">
                                Data de Aplicação
                            </label>
                            <input type="date" 
                                   name="data_aplicacao" 
                                   id="data_aplicacao"
                                   value="{{ old('data_aplicacao') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('data_aplicacao')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <p class="text-sm text-gray-600">
                                <strong>Nota:</strong> A data de vencimento será calculada automaticamente baseada na validade da vacina selecionada.
                            </p>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('carteira-vacina.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Adicionar Vacina
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
