<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user"]) || !isset($_SESSION["user"]["userId"])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["user"]["userId"];
$api_url = "http://localhost:8080/automarket/publicaciones/listarPublicaciones";
$response = file_get_contents($api_url);
$publicaciones = json_decode($response, true);

// Filtrar publicaciones según los parámetros de búsqueda
$marcaFiltro = $_GET['marca'] ?? '';
$modeloFiltro = $_GET['modelo'] ?? '';
$anoMinFiltro = $_GET['ano_min'] ?? '';
$anoMaxFiltro = $_GET['ano_max'] ?? '';
$precioMinFiltro = $_GET['precio_min'] ?? '';
$precioMaxFiltro = $_GET['precio_max'] ?? '';

$publicacionesFiltradas = array_filter($publicaciones, function($auto) use ($marcaFiltro, $modeloFiltro, $anoMinFiltro, $anoMaxFiltro, $precioMinFiltro, $precioMaxFiltro) {
    return (!$marcaFiltro || stripos($auto['marca'], $marcaFiltro) !== false) &&
           (!$modeloFiltro || stripos($auto['modelo'], $modeloFiltro) !== false) &&
           (!$anoMinFiltro || $auto['ano'] >= $anoMinFiltro) &&
           (!$anoMaxFiltro || $auto['ano'] <= $anoMaxFiltro) &&
           (!$precioMinFiltro || $auto['precio'] >= $precioMinFiltro) &&
           (!$precioMaxFiltro || $auto['precio'] <= $precioMaxFiltro);
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicaciones | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">🚗 Autos Publicados</h2>
        
        <!-- Formulario de filtro -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-2">
                    <input type="text" name="marca" class="form-control" placeholder="Marca" value="<?= htmlspecialchars($marcaFiltro) ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" name="modelo" class="form-control" placeholder="Modelo" value="<?= htmlspecialchars($modeloFiltro) ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="ano_min" class="form-control" placeholder="Año mínimo" value="<?= htmlspecialchars($anoMinFiltro) ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="ano_max" class="form-control" placeholder="Año máximo" value="<?= htmlspecialchars($anoMaxFiltro) ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="precio_min" class="form-control" placeholder="Precio mínimo" value="<?= htmlspecialchars($precioMinFiltro) ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="precio_max" class="form-control" placeholder="Precio máximo" value="<?= htmlspecialchars($precioMaxFiltro) ?>">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">🔍 Filtrar</button>
                </div>
            </div>
        </form>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($publicacionesFiltradas)): ?>
                    <?php foreach ($publicacionesFiltradas as $auto): ?>
                        <tr>
                            <td><?= htmlspecialchars($auto["marca"]) ?></td>
                            <td><?= htmlspecialchars($auto["modelo"]) ?></td>
                            <td><?= htmlspecialchars($auto["ano"]) ?></td>
                            <td>$<?= number_format($auto["precio"]) ?></td>
                            <td>
                                <?php if ($auto["userId"] == $userId): ?>
                                    <a href="actualizarPublicaciones.php?idPublicacion=<?= $auto["idPublicacion"] ?>" class="btn btn-warning">✏️ Editar</a>
                                    <a href="eliminarPublicaciones.php?idPublicacion=<?= $auto["idPublicacion"] ?>" class="btn btn-danger">🗑️ Eliminar</a>
                                <?php else: ?>
                                    <!-- Botón para iniciar trámite -->
                                    <button class="btn btn-success iniciar-tramite"
                                        data-id="<?= $auto["idPublicacion"] ?>"
                                        data-comprador="<?= $userId ?>">
                                        Iniciar Trámite
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay autos publicados con esos criterios.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>   
        <a href="perfil.php" class="btn btn-secondary">🔙 Volver</a>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const botones = document.querySelectorAll(".iniciar-tramite");

        botones.forEach(boton => {
            boton.addEventListener("click", function () {
                const idPublicacion = this.getAttribute("data-id");
                const idComprador = this.getAttribute("data-comprador");

                fetch("http://localhost:8082/api/tramites", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ idPublicacion, idComprador })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.mensaje); // Mensaje de éxito o error
                    window.location.href = "misTramites.php";
                })
                .catch(error => {
                    console.error("Error al iniciar trámite:", error);
                    alert("Hubo un problema al iniciar el trámite.");
                });
            });
        });
    });
    </script>

</body>
</html>