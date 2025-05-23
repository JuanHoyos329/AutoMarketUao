<?php
$csvFile = 'consultas_total.csv';
$datos = [];

if (($handle = fopen($csvFile, 'r')) !== false) {
    $headers = fgetcsv($handle);
    while (($row = fgetcsv($handle)) !== false) {
        $categoria = $row[0];
        $valor = (float)$row[1];
        $consulta = $row[2];

        if (!isset($datos[$consulta])) {
            $datos[$consulta] = [];
        }

        $datos[$consulta][] = [
            'categoria' => $categoria,
            'valor' => $valor
        ];
    }
    fclose($handle);
}

function tipoVisualizacion($consulta) {
    return match($consulta) {
        "Trámites por estado" => "bar",
        "Paso donde más se cancelan trámites" => "pie",
        "Promedio de duración de trámites" => "boton",
        "Promedio días publicación a finalización" => "boton",
        "Promedio precio por marca y año" => "horizontalBar",
        "Porcentaje por ciudad" => "tabla",
        "Ventas por marca" => "doughnut",
        default => "bar",
    };
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Dashboard de trámites</title>

<!-- Bootstrap 5 CSS para diseño responsivo -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body {
        background: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 2rem;
    }
    h1 {
        color: #212529;
        font-weight: 700;
        margin-bottom: 2rem;
        text-align: center;
        letter-spacing: 1.5px;
    }
    .card {
        border-radius: 1rem;
        box-shadow: 0 8px 20px rgb(0 0 0 / 0.1);
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgb(0 0 0 / 0.15);
    }
    .card-header {
        font-weight: 600;
        font-size: 1.25rem;
        background: linear-gradient(90deg, #0d6efd 0%, #6610f2 100%);
        color: white;
        border-radius: 1rem 1rem 0 0;
        text-align: center;
    }
    .value-button {
        font-size: 2.5rem;
        color: #0d6efd;
        font-weight: 700;
        padding: 1.5rem;
        border-radius: 1rem;
        background: #e7f1ff;
        margin: 2rem auto;
        width: fit-content;
        box-shadow: 0 4px 15px rgb(13 110 253 / 0.3);
        user-select: none;
    }
    table {
        margin-top: 1rem;
    }
    th {
        background-color: #0d6efd;
        color: white;
        text-align: center;
    }
    td, th {
        padding: 0.75rem;
        text-align: center;
    }
    select.form-select {
        max-width: 200px;
        margin-bottom: 1rem;
    }
    canvas {
        max-height: 400px !important;
    }
</style>
</head>
<body>

<h1>Dashboard de trámites 📊  </h1>

<div class="container">
    
    <!-- Trámites por estado -->
    <div class="row g-4 mb-5 justify-content-center">
        <?php
        $tramites = ['Finalizado' => 0, 'Cancelado' => 0, 'activo' => 0];
        $totalTramites = 0;
        foreach ($datos['1 - Trámites por estado'] ?? [] as $row) {
            $tramites[$row['categoria']] = intval($row['valor']);
            $totalTramites += intval($row['valor']);
        }
        foreach ($tramites as $estado => $valor):
        ?>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card text-center p-4">
                <div class="card-header"><?= htmlspecialchars($estado) ?></div>
                <div class="value-button"><?= $valor ?></div>
                <div class="text-muted fw-semibold">
                    <?= $totalTramites > 0 ? round(($valor / $totalTramites) * 100, 2) . '%' : '0%' ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Promedios en botones -->
    <div class="row g-4 mb-5 justify-content-center">
        <?php
        $consultasBoton = [
            '5 - Promedio días publicación a finalización' => 'Promedio de días desde la creación de la publicación hasta la finalización del tramite',
        ];
        foreach ($consultasBoton as $key => $label):
            $valor = 0;
            if (isset($datos[$key]) && count($datos[$key]) > 0) {
                $valor = $datos[$key][0]['valor'];
            }
        ?>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card text-center p-4">
                <div class="card-header"><?= $label ?></div>
                <div class="value-button"><?= $valor ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>


    <!-- Paso donde más se cancelan trámites - gráfica barra -->
    <div class="card p-4 mb-5">
        <div class="card-header">Paso donde más se cancelan trámites</div>
        <canvas id="graficaCancelaciones"></canvas>
    </div>
    
    <!-- Publicaciones por marca - gráfica pie -->
    <div class="card p-4 mb-5">
        <div class="card-header">Porcentaje de Publicaciones por marca</div>
        <canvas id="publicacionesPieChart"></canvas>
    </div>

    <?php
$promPrecioItems = $datos['7 - Promedio precio por marca y año'] ?? [];

// Separar marcas y modelos
$marcas = [];
$modelos = [];
foreach ($promPrecioItems as $item) {
    $partes = explode(' ', $item['categoria']);
    $marca = $partes[0] ?? '';
    $modelo = $partes[1] ?? '';
    if ($marca) $marcas[] = $marca;
    if ($modelo) $modelos[] = $modelo;
}

$marcas = array_unique($marcas);
$modelos = array_unique($modelos);
sort($marcas);
sort($modelos);
?>

<div class="card p-4 mb-5">
    <div class="card-header">Promedio precio por marca y año</div>

    <div class="row mb-3 mt-2">
        <div class="col">
            <label for="marcaFiltroPrecio">Filtrar por marca:</label>
            <select id="marcaFiltroPrecio" class="form-select">
                <option value="">Todas</option>
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?= htmlspecialchars($marca) ?>"><?= htmlspecialchars($marca) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col">
            <label for="modeloFiltroPrecio">Filtrar por modelo:</label>
            <select id="modeloFiltroPrecio" class="form-select">
                <option value="">Todos</option>
                <?php foreach ($modelos as $modelo): ?>
                    <option value="<?= htmlspecialchars($modelo) ?>"><?= htmlspecialchars($modelo) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <canvas id="promPrecioChart"></canvas>
</div>

<script>
    const allPrecioItems = <?= json_encode($promPrecioItems) ?>;

    function filtrarPorMarcaYModelo(marca, modelo) {
        let filtrados = allPrecioItems.filter(item => {
            const [itemMarca, itemModelo] = item.categoria.split(' ');
            const coincideMarca = !marca || itemMarca === marca;
            const coincideModelo = !modelo || itemModelo === modelo;
            return coincideMarca && coincideModelo;
        });

        const etiquetas = filtrados.map(item => item.categoria);
        const valores = filtrados.map(item => item.valor);

        precioChart.data.labels = etiquetas;
        precioChart.data.datasets[0].data = valores;
        precioChart.update();
    }

    const ctxPrecio = document.getElementById('promPrecioChart').getContext('2d');
    const precioChart = new Chart(ctxPrecio, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Precio promedio',
                data: [],
                backgroundColor: '#36A2EB',
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // Eventos
    document.getElementById('marcaFiltroPrecio').addEventListener('change', actualizarFiltro);
    document.getElementById('modeloFiltroPrecio').addEventListener('change', actualizarFiltro);

    function actualizarFiltro() {
        const marca = document.getElementById('marcaFiltroPrecio').value;
        const modelo = document.getElementById('modeloFiltroPrecio').value;
        filtrarPorMarcaYModelo(marca, modelo);
    }

    // Inicializar
    filtrarPorMarcaYModelo('', '');
</script>


    <!-- Ventas por marca - gráfica doughnut -->
    <div class="card p-4 mb-5">
        <div class="card-header">Total de ventas por marca</div>
        <canvas id="ventasDoughnutChart"></canvas>
    </div>

    <!-- Porcentaje por ciudad - tabla -->
    <div class="card p-4 mb-5">
        <div class="card-header">Porcentaje de Autos vendidos por ciudad</div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr><th>Categoría</th><th>Porcentaje</th></tr>
            </thead>
            <tbody>
                <?php foreach ($datos['8 - Porcentaje por ciudad'] ?? [] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['categoria']) ?></td>
                    <td><?= htmlspecialchars($item['valor']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    

</div>

<script>
    // Paso donde más se cancelan trámites - barra
    const cancelLabels = <?= json_encode(array_map(fn($item) => $item['categoria'], $datos['2 - Paso donde más se cancelan trámites'] ?? [])) ?>;
    const cancelValues = <?= json_encode(array_map(fn($item) => $item['valor'], $datos['2 - Paso donde más se cancelan trámites'] ?? [])) ?>;
    const ctxCancel = document.getElementById('graficaCancelaciones').getContext('2d');
    new Chart(ctxCancel, {
        type: 'bar',
        data: {
            labels: cancelLabels,
            datasets: [{
                label: 'Trámites cancelados',
                data: cancelValues,
                backgroundColor: cancelLabels.map((_, i) => `hsl(${i*45}, 70%, 60%)`),
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false },
            },
            scales: {
                y: { beginAtZero: true },
                x: {
                    ticks: { maxRotation: 45, minRotation: 30 }
                }
            }
        }
    });



    // Publicaciones por marca - pie
    const pubLabels = <?= json_encode(array_map(fn($item) => $item['categoria'], $datos['6 - Publicaciones por marca'] ?? [])) ?>;
    const pubValues = <?= json_encode(array_map(fn($item) => $item['valor'], $datos['6 - Publicaciones por marca'] ?? [])) ?>;
    const pubColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

    const ctxPie = document.getElementById('publicacionesPieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: pubLabels,
            datasets: [{
                data: pubValues,
                backgroundColor: pubLabels.map((_, i) => pubColors[i % pubColors.length]),
                hoverOffset: 20,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'right' },
                tooltip: { enabled: true }
            }
        }
    });

    // Ventas por marca - doughnut
    const ventasLabels = <?= json_encode(array_map(fn($item) => $item['categoria'], $datos['Ventas por marca'] ?? [])) ?>;
    const ventasValues = <?= json_encode(array_map(fn($item) => $item['valor'], $datos['Ventas por marca'] ?? [])) ?>;
    const ventasColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4DB6AC', '#BA68C8'];

    const ctxDoughnut = document.getElementById('ventasDoughnutChart').getContext('2d');
    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: ventasLabels,
            datasets: [{
                data: ventasValues,
                backgroundColor: ventasLabels.map((_, i) => ventasColors[i % ventasColors.length]),
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 30
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { enabled: true }
            }
        }
    });
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
