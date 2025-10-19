<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard DEMAS - Porto Velho/RO') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Controles -->
            <div class="mb-6 flex justify-between items-center">
                <div class="flex space-x-4">
                    <button onclick="loadUnidades()" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Carregar Unidades
                    </button>
                    <button onclick="loadVacinas()" 
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Carregar Vacinas
                    </button>
                    <button onclick="loadDadosRondonia()" 
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                        Carregar Dados RO
                    </button>
                </div>
                <button onclick="clearCache()" 
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Limpar Cache
                </button>
            </div>

            <!-- Loading Indicator -->
            <div id="loading" class="hidden bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                Carregando dados do DEMAS...
            </div>

            <!-- Error Messages -->
            <div id="error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            </div>

            <!-- Success Messages -->
            <div id="success" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            </div>

            <!-- Unidades de Saúde -->
            <div class="mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Unidades de Saúde - Porto Velho/RO</h3>
                        <div id="unidades-content">
                            <p class="text-gray-500">Clique em "Carregar Unidades" para buscar dados do DEMAS.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vacinas Distribuídas -->
            <div class="mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Vacinas Distribuídas - Porto Velho/RO</h3>
                        <div id="vacinas-content">
                            <p class="text-gray-500">Clique em "Carregar Vacinas" para buscar dados do DEMAS.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dados Gerais de Rondônia -->
            <div class="mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Dados Gerais - Rondônia</h3>
                        <div id="rondonia-content">
                            <p class="text-gray-500">Clique em "Carregar Dados RO" para buscar dados do DEMAS.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLoading() {
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('error').classList.add('hidden');
            document.getElementById('success').classList.add('hidden');
        }

        function hideLoading() {
            document.getElementById('loading').classList.add('hidden');
        }

        function showError(message) {
            document.getElementById('error').textContent = message;
            document.getElementById('error').classList.remove('hidden');
            document.getElementById('success').classList.add('hidden');
        }

        function showSuccess(message) {
            document.getElementById('success').textContent = message;
            document.getElementById('success').classList.remove('hidden');
            document.getElementById('error').classList.add('hidden');
        }

        function loadUnidades() {
            showLoading();
            fetch('{{ route("admin.demas.unidades") }}')
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        displayUnidades(data.data);
                        showSuccess(data.message);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    showError('Erro ao carregar unidades: ' + error.message);
                });
        }

        function loadVacinas() {
            showLoading();
            fetch('{{ route("admin.demas.vacinas") }}')
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        displayVacinas(data.data);
                        showSuccess(data.message);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    showError('Erro ao carregar vacinas: ' + error.message);
                });
        }

        function loadDadosRondonia() {
            showLoading();
            fetch('{{ route("admin.demas.rondonia") }}')
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        displayDadosRondonia(data.data);
                        showSuccess(data.message);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    showError('Erro ao carregar dados de Rondônia: ' + error.message);
                });
        }

        function clearCache() {
            showLoading();
            fetch('{{ route("admin.demas.clear-cache") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showSuccess(data.message);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                hideLoading();
                showError('Erro ao limpar cache: ' + error.message);
            });
        }

        function displayUnidades(data) {
            const content = document.getElementById('unidades-content');
            if (data && data.length > 0) {
                let html = '<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">';
                html += '<thead class="bg-gray-50"><tr>';
                html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>';
                html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Endereço</th>';
                html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telefone</th>';
                html += '</tr></thead><tbody class="bg-white divide-y divide-gray-200">';
                
                data.forEach(unidade => {
                    html += '<tr>';
                    html += `<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${unidade.nome || 'N/A'}</td>`;
                    html += `<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${unidade.endereco || 'N/A'}</td>`;
                    html += `<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${unidade.telefone || 'N/A'}</td>`;
                    html += '</tr>';
                });
                
                html += '</tbody></table></div>';
                content.innerHTML = html;
            } else {
                content.innerHTML = '<p class="text-gray-500">Nenhuma unidade encontrada.</p>';
            }
        }

        function displayVacinas(data) {
            const content = document.getElementById('vacinas-content');
            if (data && data.length > 0) {
                let html = '<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">';
                html += '<thead class="bg-gray-50"><tr>';
                html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vacina</th>';
                html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantidade</th>';
                html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data Distribuição</th>';
                html += '</tr></thead><tbody class="bg-white divide-y divide-gray-200">';
                
                data.forEach(vacina => {
                    html += '<tr>';
                    html += `<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${vacina.nome || 'N/A'}</td>`;
                    html += `<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${vacina.quantidade || 'N/A'}</td>`;
                    html += `<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${vacina.data_distribuicao || 'N/A'}</td>`;
                    html += '</tr>';
                });
                
                html += '</tbody></table></div>';
                content.innerHTML = html;
            } else {
                content.innerHTML = '<p class="text-gray-500">Nenhuma vacina encontrada.</p>';
            }
        }

        function displayDadosRondonia(data) {
            const content = document.getElementById('rondonia-content');
            if (data) {
                let html = '<div class="grid grid-cols-1 md:grid-cols-3 gap-6">';
                
                // Estatísticas gerais
                if (data.total_vacinas) {
                    html += '<div class="bg-blue-50 p-6 rounded-lg">';
                    html += '<h4 class="text-lg font-semibold text-blue-800">Total de Vacinas</h4>';
                    html += `<p class="text-3xl font-bold text-blue-600">${data.total_vacinas}</p>`;
                    html += '</div>';
                }
                
                if (data.total_unidades) {
                    html += '<div class="bg-green-50 p-6 rounded-lg">';
                    html += '<h4 class="text-lg font-semibold text-green-800">Total de Unidades</h4>';
                    html += `<p class="text-3xl font-bold text-green-600">${data.total_unidades}</p>`;
                    html += '</div>';
                }
                
                if (data.ultima_atualizacao) {
                    html += '<div class="bg-purple-50 p-6 rounded-lg">';
                    html += '<h4 class="text-lg font-semibold text-purple-800">Última Atualização</h4>';
                    html += `<p class="text-sm text-purple-600">${data.ultima_atualizacao}</p>`;
                    html += '</div>';
                }
                
                html += '</div>';
                
                // Dados detalhados se disponíveis
                if (data.detalhes) {
                    html += '<div class="mt-6">';
                    html += '<h4 class="text-lg font-semibold mb-4">Detalhes</h4>';
                    html += '<pre class="bg-gray-100 p-4 rounded overflow-x-auto">';
                    html += JSON.stringify(data.detalhes, null, 2);
                    html += '</pre>';
                    html += '</div>';
                }
                
                content.innerHTML = html;
            } else {
                content.innerHTML = '<p class="text-gray-500">Nenhum dado encontrado.</p>';
            }
        }
    </script>
</x-app-layout>
