<?php
// Función para leer CSV limpiando encabezados extra
function leerCSV($ruta) {
    $datos = [];
    if (($gestor = fopen($ruta, "r")) !== FALSE) {
        $encabezados = fgetcsv($gestor);
        $indiceEstado = array_search("estado", $encabezados);
        $encabezados = array_slice($encabezados, 0, $indiceEstado + 1);

        while (($fila = fgetcsv($gestor)) !== FALSE) {
            $fila = array_slice($fila, 0, count($encabezados));
            if (count($fila) === count($encabezados)) {
                $datos[] = array_combine($encabezados, $fila);
            }
        }
        fclose($gestor);
    }
    return $datos;
}

function leerCSVPublicaciones($ruta) {
    $datos = [];
    if (($gestor = fopen($ruta, "r")) !== FALSE) {
        $encabezados = fgetcsv($gestor);
        while (($fila = fgetcsv($gestor)) !== FALSE) {
            if (count($fila) === count($encabezados)) {
                $filaAsociativa = array_combine($encabezados, $fila);
                // Asociamos por idPublicacion para acceso rápido
                $datos[$filaAsociativa['idPublicacion']] = $filaAsociativa;
            }
        }
        fclose($gestor);
    }
    return $datos;
}

// Función para quitar tildes
function quitarTildes($cadena) {
    $originales = 'áéíóúÁÉÍÓÚñÑ';
    $modificadas = 'aeiouAEIOUnN';
    return strtr($cadena, $originales, $modificadas);
}

// Leer archivo CSV
$tramites = leerCSV("backtramites.csv");
$publicacionesRaw = leerCSV("publicaciones.csv");
$publicaciones = [];
foreach ($publicacionesRaw as $publi) {
    $id = $publi["idPublicacion"];
    $publicaciones[$id] = $publi;
}

// Contar trámites por estado
$conteoEstados = [
    "Finalizado" => 0,
    "Cancelado" => 0,
    "activo" => 0
];
foreach ($tramites as $t) {
    $estado = strtolower(trim($t["estado"]));
    if ($estado === "finalizado") $conteoEstados["Finalizado"]++;
    if ($estado === "cancelado")  $conteoEstados["Cancelado"]++;
    if ($estado === "activo")     $conteoEstados["activo"]++;
}

// Calcular porcentaje por estado
$totalTramites = array_sum($conteoEstados);
$porcentajes = [];
foreach ($conteoEstados as $estado => $cantidad) {
    $porcentajes[$estado] = $totalTramites > 0 ? round(($cantidad / $totalTramites) * 100, 2) : 0;
}

// Analizar pasos donde más se cancelan
$pasos = ["revision_doc", "cita", "contrato", "pago", "Traspaso", "entrega"];
$cancelacionesPorPaso = array_fill_keys($pasos, 0);

foreach ($tramites as $t) {
    if (strtolower(trim($t["estado"])) === "cancelado") {
        foreach ($pasos as $paso) {
            if (isset($t[$paso]) && $t[$paso] == "0") {
                $cancelacionesPorPaso[$paso]++;
            }
        }
    }
}

// 📊 Calcular promedio de días desde publicación hasta finalización
$totalDiasPublicacion = [];

foreach ($tramites as $tramite) {
    if (strtolower($tramite["estado"]) === "finalizado") {
        $idVehiculo = $tramite["id_vehiculo"];
        if (isset($publicaciones[$idVehiculo])) {
            $fechaPublicacion = new DateTime($publicaciones[$idVehiculo]["fecha_publicacion"]);
            $fechaFin = new DateTime($tramite["fecha_fin"]);

            $interval = $fechaPublicacion->diff($fechaFin);
            $totalDiasPublicacion[] = $interval->days;
        }
    }
}

// 📊 Conteo de publicaciones por marca
$conteoMarcas = [];

foreach ($publicaciones as $publi) {
    $marca = $publi["marca"];
    if (!isset($conteoMarcas[$marca])) {
        $conteoMarcas[$marca] = 0;
    }
    $conteoMarcas[$marca]++;
}

$promedioDiasPublicacionAFin = count($totalDiasPublicacion) > 0
    ? round(array_sum($totalDiasPublicacion) / count($totalDiasPublicacion))
    : 0;

// 🔍 Leer publicaciones y agrupar por marca
$publicaciones = leerCSV("publicaciones.csv");

$datosPorMarca = [];

foreach ($publicaciones as $publi) {
    $marca = $publi["marca"];
    $modelo = $publi["modelo"];
    $ano = $publi["ano"];
    $precio = (float) $publi["precio"];

    $clave = $modelo . " " . $ano;

    if (!isset($datosPorMarca[$marca][$clave])) {
        $datosPorMarca[$marca][$clave] = ["total" => 0, "conteo" => 0];
    }

    $datosPorMarca[$marca][$clave]["total"] += $precio;
    $datosPorMarca[$marca][$clave]["conteo"] += 1;
}

// 💰 Calcular promedios
$promediosPorMarca = [];
foreach ($datosPorMarca as $marca => $modelos) {
    foreach ($modelos as $modeloAno => $valores) {
        $promedio = round($valores["total"] / $valores["conteo"], 2);
        $promediosPorMarca[$marca][$modeloAno] = $promedio;
    }
}

// 📍 Agrupar publicaciones por ciudad
$conteoPorCiudad = [];

foreach ($publicaciones as $publi) {
    $ciudad = $publi["ubicacion"];

    if (!isset($conteoPorCiudad[$ciudad])) {
        $conteoPorCiudad[$ciudad] = 0;
    }
    $conteoPorCiudad[$ciudad]++;
}

// 🎯 Calcular porcentaje por ciudad
$totalPublicaciones = array_sum($conteoPorCiudad);
$porcentajePorCiudad = [];

foreach ($conteoPorCiudad as $ciudad => $cantidad) {
    $porcentaje = round(($cantidad / $totalPublicaciones) * 100, 2);
    $porcentajePorCiudad[$ciudad] = $porcentaje;
}

// 📍 Conteo de publicaciones por ciudad (solo ciudades válidas)
$ciudadesValidas = [
    "bogota", "medellin", "cali", "barranquilla", "cartagena", "cucuta",
    "bucaramanga", "pereira", "santa marta", "ibague", "manizales", "villavicencio",
    "pasto", "neiva", "armenia", "monteria", "popayan", "sincelejo", "valledupar",
    "tunja", "riohacha", "quibdo", "florencia", "yopal", "mocoa", "leticia"
    // Agrega más si lo deseas
];

$conteoPorCiudad = [];
foreach ($publicaciones as $publi) {
    $ciudadOriginal = trim($publi["ubicacion"]);
    $ciudadNormalizada = strtolower(quitarTildes($ciudadOriginal));
    if (in_array($ciudadNormalizada, $ciudadesValidas)) {
        $ciudadMostrar = ucwords($ciudadOriginal); // Muestra con tildes si las tiene
        if (!isset($conteoPorCiudad[$ciudadMostrar])) {
            $conteoPorCiudad[$ciudadMostrar] = 0;
        }
        $conteoPorCiudad[$ciudadMostrar]++;
    }
}

// 🎯 Calcular porcentaje por ciudad y top 10
$totalPublicaciones = array_sum($conteoPorCiudad);
$porcentajePorCiudad = [];
foreach ($conteoPorCiudad as $ciudad => $cantidad) {
    $porcentajePorCiudad[$ciudad] = $totalPublicaciones > 0 ? round(($cantidad / $totalPublicaciones) * 100, 2) : 0;
}
arsort($porcentajePorCiudad);
$topCiudades = array_slice($porcentajePorCiudad, 0, 10, true);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Trámites | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">📊 Dashboard de Trámites - AutoMarketUAO</h2>

        <!-- Tarjetas resumen -->
        <div class="row text-center mb-4">
            <?php foreach ($conteoEstados as $estado => $cantidad): ?>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5><?= ucfirst($estado) ?></h5>
                        <p class="fs-3"><?= $cantidad ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Porcentajes por estado -->
        <div class="text-center mb-5">
            <h5>📌 Porcentaje de trámites por estado:</h5>
            <ul class="list-unstyled">
                <?php foreach ($porcentajes as $estado => $porc): ?>
                    <li><strong><?= ucfirst($estado) ?>:</strong> <?= $porc ?>%</li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Gráfico de pastel -->
        <div class="card shadow mb-4">
            <div class="card-body d-flex justify-content-center">
                <div style="max-width:350px; width:100%;">
                    <h5 class="text-center">Distribución de Trámites por Estado</h5>
                    <canvas id="graficoEstados" height="60"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de barras: pasos con más cancelaciones -->
        <div class="card shadow mb-5">
            <div class="card-body d-flex justify-content-center">
                <div style="max-width:600px; width:100%;">
                    <h5 class="text-center">📉 Paso donde más se cancelan los trámites</h5>
                    <canvas id="graficoCancelaciones" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- Promedio de días desde publicación hasta finalización -->
        <div class="card shadow mt-4 mb-5">
            <div class="card-body text-center">
                <h5 class="mb-3">⏳ Promedio de Días desde la Publicación hasta la Finalización</h5>
                <h2 class="text-primary"><?= $promedioDiasPublicacionAFin ?> días</h2>
                <p class="text-muted">Calculado solo para los trámites finalizados</p>
            </div>
        </div>

        <!-- Gráfico de publicaciones por marca -->
        <div class="card shadow mb-4 mt-4">
            <div class="card-body">
                <h5 class="text-center mb-3">🚘 Publicaciones por Marca</h5>
                <canvas id="graficoMarcas" height="100"></canvas>
            </div>
        </div>

        <!-- Filtro y gráfico de promedio por modelo y año -->
        <div class="card shadow mb-4 mt-4">
            <div class="card-body">
                <h5 class="text-center">💸 Promedio de Precio por Modelo y Año (por Marca)</h5>
                <div class="mb-3 text-center">
                    <label for="marcaSelect" class="form-label">Selecciona una marca:</label>
                    <select id="marcaSelect" class="form-select w-50 mx-auto">
                        <?php foreach (array_keys($promediosPorMarca) as $marca): ?>
                            <option value="<?= $marca ?>"><?= $marca ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <canvas id="graficoPrecioMarca" height="120"></canvas>
            </div>
        </div>
        
        <!-- ... (en la sección HTML donde quieras el gráfico) ... -->
        <div class="card shadow mt-4 mb-5">
            <div class="card-body">
                <h5 class="text-center">📍 Porcentaje de Publicaciones por Ciudad (Top 10)</h5>
                <canvas id="graficoCiudades"></canvas>
            </div>
        </div>
    <script>
    // Gráfico de pastel
    const ctx = document.getElementById("graficoEstados").getContext("2d");
    new Chart(ctx, {
        type: "pie",
        data: {
            labels: <?= json_encode(array_keys($conteoEstados)) ?>,
            datasets: [{
                label: "Trámites",
                data: <?= json_encode(array_values($conteoEstados)) ?>,
                backgroundColor: ["#198754", "#dc3545", "#0d6efd"],
                borderColor: "#fff",
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "bottom"
                }
            }
        }
    });

    // Gráfico de barras: cancelaciones por paso
    const cancelCtx = document.getElementById("graficoCancelaciones").getContext("2d");
    new Chart(cancelCtx, {
        type: "bar",
        data: {
            labels: <?= json_encode(array_keys($cancelacionesPorPaso)) ?>,
            datasets: [{
                label: "Trámites cancelados con ese paso en 0",
                data: <?= json_encode(array_values($cancelacionesPorPaso)) ?>,
                backgroundColor: "#dc3545"
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: "Cantidad de cancelaciones"
                    }
                }
            }
        }
    });

    const ctxMarcas = document.getElementById("graficoMarcas").getContext("2d");

    //Publicaciones por Marca
    new Chart(ctxMarcas, {
        type: "bar",
        data: {
            labels: <?= json_encode(array_keys($conteoMarcas)) ?>,
            datasets: [{
                label: "Cantidad de publicaciones",
                data: <?= json_encode(array_values($conteoMarcas)) ?>,
                backgroundColor: "#0d6efd"
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    const datosPorMarca = <?= json_encode($promediosPorMarca) ?>;

    const ctxMarca = document.getElementById("graficoPrecioMarca").getContext("2d");
    let grafico;

    function crearGrafico(marca) {
        const datos = datosPorMarca[marca];
        const labels = Object.keys(datos);
        const valores = Object.values(datos);

        if (grafico) {
            grafico.destroy();
        }

        grafico = new Chart(ctxMarca, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [{
                    label: `Promedio de precios - ${marca}`,
                    data: valores,
                    backgroundColor: "#0d6efd"
                }]
            },
            options: {
                responsive: true,
                indexAxis: "y",
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => "$" + ctx.raw } }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: { display: true, text: "Precio en USD" }
                    }
                }
            }
        });
    }

    // Inicializar con la primera marca
    const selector = document.getElementById("marcaSelect");
    crearGrafico(selector.value);

    // Cambiar gráfico al seleccionar otra marca
    selector.addEventListener("change", function () {
        crearGrafico(this.value);
    });

    // Gráfico de pastel: porcentaje de publicaciones por ciudad (Top 10)
    const ctxCiudades = document.getElementById("graficoCiudades").getContext("2d");
    new Chart(ctxCiudades, {
        type: "pie",
        data: {
            labels: <?= json_encode(array_keys($topCiudades)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($topCiudades)) ?>,
                backgroundColor: [
                    "#0d6efd", "#198754", "#dc3545", "#ffc107", "#6f42c1",
                    "#fd7e14", "#20c997", "#6610f2", "#e83e8c", "#6c757d"
                ]
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: "bottom"
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ": " + context.parsed + "%";
                        }
                    }
                }
            }
        }
    });
    </script>
</body>
</html>

