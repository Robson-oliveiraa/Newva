<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estatísticas para Administradores -->
            @if(auth()->user()->hasRole('administrator'))
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h5 class="font-semibold text-blue-800">Total de Usuários</h5>
                        <p class="text-3xl font-bold text-blue-600">{{ $totalUsuarios ?? 0 }}</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h5 class="font-semibold text-green-800">Médicos Ativos</h5>
                        <p class="text-3xl font-bold text-green-600">{{ $totalMedicos ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <h5 class="font-semibold text-yellow-800">Vacinas Aplicadas</h5>
                        <p class="text-3xl font-bold text-yellow-600">{{ $totalVacinasAplicadas ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h5 class="font-semibold text-purple-800">Total de Consultas</h5>
                        <p class="text-3xl font-bold text-purple-600">{{ $totalConsultas ?? 0 }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Calendário -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Calendário de Vacinas e Consultas</h3>
                            <div id="calendar" class="w-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="space-y-6">
                    <!-- Consultas -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold text-blue-800 mb-2">Consultas</h4>
                        <p class="text-blue-600 mb-4">Gerencie suas consultas médicas</p>
                        <div class="space-y-2">
                            <a href="{{ route('consultas.index') }}" 
                               class="block w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                                Ver Consultas
                            </a>
                            @can('consultas.create')
                                <a href="{{ route('consultas.create') }}" 
                                   class="block w-full bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded text-center">
                                    Agendar Consulta
                                </a>
                            @endcan
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
                            @can('carteira-vacina.create')
                                <a href="{{ route('carteira-vacina.create') }}" 
                                   class="block w-full bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded text-center">
                                    Aplicar Vacina
                                </a>
                            @endcan
                        </div>
                    </div>

                    <!-- Links Administrativos -->
                    @if(auth()->user()->hasRole('administrator'))
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-purple-800 mb-2">Administração</h4>
                            <div class="space-y-2">
                                <a href="{{ route('admin.medicos.index') }}" 
                                   class="block w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-center">
                                    Gerenciar Médicos
                                </a>
                                <a href="{{ route('admin.demas.dashboard') }}" 
                                   class="block w-full bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded text-center">
                                    Dashboard DEMAS
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/pt-br.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const eventos = @json($eventos ?? []);
            
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listWeek'
                },
                events: eventos.map(evento => ({
                    id: evento.id,
                    title: evento.title,
                    start: evento.date,
                    backgroundColor: getEventColor(evento.type),
                    borderColor: getEventColor(evento.type),
                    extendedProps: evento
                })),
                eventClick: function(info) {
                    const evento = info.event.extendedProps;
                    let mensagem = `Título: ${evento.title}\n`;
                    mensagem += `Data: ${info.event.start.toLocaleDateString('pt-BR')}\n`;
                    
                    if (evento.medico) mensagem += `Médico: ${evento.medico}\n`;
                    if (evento.paciente) mensagem += `Paciente: ${evento.paciente}\n`;
                    if (evento.time) mensagem += `Horário: ${evento.time}\n`;
                    if (evento.status) mensagem += `Status: ${evento.status}\n`;
                    
                    alert(mensagem);
                }
            });
            
            calendar.render();
        });

        function getEventColor(type) {
            switch(type) {
                case 'aplicada': return '#10B981'; // Verde
                case 'vencendo': return '#F59E0B'; // Amarelo
                case 'consulta': return '#3B82F6'; // Azul
                default: return '#6B7280'; // Cinza
            }
        }
    </script>
</x-app-layout>
