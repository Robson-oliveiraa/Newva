<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Postos de Saúde - Porto Velho') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-2">Localização dos Postos de Saúde</h3>
                        <p class="text-gray-600">
                            Encontre os postos de saúde mais próximos de você em Porto Velho
                        </p>
                    </div>

                    <!-- Mapa -->
                    <div class="mb-6">
                        <div id="map" style="height: 500px; width: 100%; border-radius: 8px;"></div>
                    </div>

                    <!-- Lista de Postos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($postos as $posto)
                            <div class="bg-gray-50">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900">{{ $posto['nome'] }}</h4>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($posto['tipo'] === 'UPA') bg-red-100 text-red-800
                                        @elseif($posto['tipo'] === 'Hospital') bg-purple-100 text-purple-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ $posto['tipo'] }}
                                    </span>
                                </div>
                                
                                <div class="space-y-1 text-sm text-gray-600">
                                    <p><strong>Endereço:</strong> {{ $posto['endereco'] }}</p>
                                    <p><strong>Telefone:</strong> {{ $posto['telefone'] }}</p>
                                    <p><strong>Horário:</strong> {{ $posto['horario'] }}</p>
                                </div>
                                
                                <button onclick="focusOnMarker({{ $posto['lat'] }}, {{ $posto['lng'] }})" 
                                        class="mt-3 w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Ver no Mapa
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Inicializar o mapa
        var map = L.map('map').setView([-8.7612, -63.9020], 12);

        // Adicionar camada de tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Dados dos postos de saúde
        var postos = @json($postos);

        // Adicionar marcadores para cada posto
        postos.forEach(function(posto) {
            var iconColor = posto.tipo === 'UPA' ? 'red' : 
                           posto.tipo === 'Hospital' ? 'purple' : 'blue';
            
            var marker = L.marker([posto.lat, posto.lng], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: ${iconColor}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(map);

            // Popup com informações do posto
            marker.bindPopup(`
                <div style="min-width: 200px;">
                    <h3 style="margin: 0 0 8px 0; font-weight: bold; color: #1f2937;">${posto.nome}</h3>
                    <p style="margin: 4px 0; font-size: 14px;"><strong>Tipo:</strong> ${posto.tipo}</p>
                    <p style="margin: 4px 0; font-size: 14px;"><strong>Endereço:</strong> ${posto.endereco}</p>
                    <p style="margin: 4px 0; font-size: 14px;"><strong>Telefone:</strong> ${posto.telefone}</p>
                    <p style="margin: 4px 0; font-size: 14px;"><strong>Horário:</strong> ${posto.horario}</p>
                </div>
            `);
        });

        // Função para focar em um marcador específico
        function focusOnMarker(lat, lng) {
            map.setView([lat, lng], 16);
        }

        // Adicionar controle de localização
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLat = position.coords.latitude;
                var userLng = position.coords.longitude;
                
                // Adicionar marcador da localização do usuário
                L.marker([userLat, userLng], {
                    icon: L.divIcon({
                        className: 'custom-div-icon',
                        html: '<div style="background-color: green; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    })
                }).addTo(map).bindPopup('Sua localização atual').openPopup();
            });
        }
    </script>

    <style>
        .custom-div-icon {
            background: transparent !important;
            border: none !important;
        }
    </style>
</x-app-layout>



