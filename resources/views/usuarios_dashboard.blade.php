<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">

    {{-- Título + volver --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="flex-grow-1 text-center">
            <h1 class="fw-bold" style="color:#28a745; text-shadow:1px 1px 2px rgba(0,0,0,.2);">
                Dashboard de Usuarios
            </h1>
        </div>
            <a href="{{ url('/') }}" class="btn btn-success d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M10.146 12.354a.5.5 0 0 0 .708-.708L8.707 10H14a.5.5 0 0 0 0-1H8.707l2.147-1.646a.5.5 0 1 0-.708-.708l-3 2.5a.5.5 0 0 0 0 .708l3 2.5z" />
                    <path fill-rule="evenodd"
                        d="M4.5 14A1.5 1.5 0 0 1 3 12.5v-9A1.5 1.5 0 0 1 4.5 2h7A1.5 1.5 0 0 1 13 3.5V6a.5.5 0 0 1-1 0V3.5a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V10a.5.5 0 0 1 1 0v2.5a1.5 1.5 0 0 1-1.5 1.5h-7z" />
                </svg>
                Volver al inicio
            </a>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Cards de totales --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">TOTAL USUARIOS</h6>
                    <div class="display-6" style="color:#28a745">{{ $totales['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">ADMINISTRADORES</h6>
                    <div class="display-6" style="color:#28a745">{{ $totales['admin'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">EMPLEADOS</h6>
                    <div class="display-6" style="color:#28a745">{{ $totales['empleado'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">VISITANTES</h6>
                    <div class="display-6" style="color:#28a745">{{ $totales['visitante'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tablas por rol --}}
    @foreach ($porRol as $rol => $lista)
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0" style="color:#28a745">{{ $rol }} ({{ count($lista) }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:120px;">Cod. Usuario</th>
                                <th>Nombre de Usuario</th>
                                <th style="width:140px;">Estado</th>
                                <th style="width:160px;">Primer Acceso</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($lista as $u)
                            <tr>
                                <td>{{ $u['cod_usuario'] ?? 'N/A' }}</td>
                                <td>{{ $u['nombre_usuario'] ?? 'N/A' }}</td>
                                <td>
                                    @php $estado = strtolower($u['estado_usuario'] ?? ''); @endphp
                                    <span class="badge {{ $estado === 'activo' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $u['estado_usuario'] ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $u['primer_acceso'] ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">Sin usuarios en este rol.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
           {{-- ==== GRÁFICAS ==== --}}
<div class="row g-3 mt-2">
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <strong style="color:#28a745">Usuarios Por Permisos</strong>
            </div>
            <div class="card-body">
                <canvas id="chartRoles" height="150"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <strong style="color:#28a745">Primer Acceso</strong>
            </div>
            <div class="card-body">
                <canvas id="chartPrimerAcceso" height="150"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <strong style="color:#28a745">Usuarios Por Estado</strong>
            </div>
            <div class="card-body">
                <canvas id="chartEstado" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- CDN Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
    // Datos para "Usuarios por permiso"
    $labelsRoles = ['Administrador', 'Empleado', 'Visitante'];
    $dataRoles = [
        isset($porRol['Administrador']) ? count($porRol['Administrador']) : 0,
        isset($porRol['Empleado'])      ? count($porRol['Empleado'])      : 0,
        isset($porRol['Visitante'])     ? count($porRol['Visitante'])     : 0,
    ];

    // Datos para "Primer acceso"
    $paSi = 0; $paNo = 0;
    foreach ($porRol as $lista) {
        foreach ($lista as $u) {
            ((int)($u['primer_acceso'] ?? 0) === 1) ? $paSi++ : $paNo++;
        }
    }
    $labelsPA = ['Sí (1)', 'No (0)'];
    $dataPA = [$paSi, $paNo];

    // Datos para "Usuarios por estado"
    $estadoActivo = 0; $estadoInactivo = 0;
    foreach ($porRol as $lista) {
        foreach ($lista as $u) {
            strtolower($u['estado_usuario'] ?? '') === 'activo' ? $estadoActivo++ : $estadoInactivo++;
        }
    }
    $labelsEstado = ['Activo', 'Inactivo'];
    $dataEstado = [$estadoActivo, $estadoInactivo];
@endphp

<script>
(function () {
    // Paleta verde del parque + tonos
    const COLORS = {
        green:  '#28a745', // principal
        mint:   '#61c27d',
        teal:   '#1e7e34',
        sage:   '#9dd8ae',
        gray:   '#6c757d',
        light:  '#d8f0df'
    };

    // Donut por permiso (más pequeño)
    new Chart(document.getElementById('chartRoles'), {
        type: 'doughnut',
        data: {
            labels: @json($labelsRoles),
            datasets: [{
                data: @json($dataRoles),
                backgroundColor: [COLORS.green, COLORS.mint, COLORS.teal],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 14 } }
            },
            cutout: '65%',
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Pie primer acceso (más pequeño)
    new Chart(document.getElementById('chartPrimerAcceso'), {
        type: 'pie',
        data: {
            labels: @json($labelsPA),
            datasets: [{
                data: @json($dataPA),
                backgroundColor: [COLORS.green, COLORS.gray],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 14 } }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Barras por estado (verde para activo, gris para inactivo)
    new Chart(document.getElementById('chartEstado'), {
        type: 'bar',
        data: {
            labels: @json($labelsEstado),
            datasets: [{
                label: 'Cantidad de usuarios',
                data: @json($dataEstado),
                backgroundColor: [COLORS.green, COLORS.gray],
                borderColor: [COLORS.teal, COLORS.gray],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: COLORS.light } },
                x: { grid: { display: false } }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
})();
</script>
</body>
</html>
