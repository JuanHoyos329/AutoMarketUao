<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    die("Error: No se proporcionó un ID de trámite.");
}

$tramiteId = $_GET["id"];
$apiUrl = "http://192.168.100.3:8082/api/tramites/" . $tramiteId; // Nueva ruta correcta

$response = file_get_contents($apiUrl);
$tramite = json_decode($response, true);

if (!$tramite || !$tramite["ok"]) {
    die("Error al obtener la información del trámite.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Trámite</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center mb-4">Detalles del Trámite</h2>
            <ul class="list-group">
                <li class="list-group-item"><strong>ID:</strong> <?= htmlspecialchars($tramite["data"]["id"]) ?></li>
                <li class="list-group-item"><strong>Estado:</strong> <?= htmlspecialchars($tramite["data"]["estado"]) ?></li>
                <li class="list-group-item"><strong>Fecha:</strong> <?= htmlspecialchars($tramite["data"]["fecha"]) ?></li>
                <li class="list-group-item"><strong>Comprador:</strong> <?= htmlspecialchars($tramite["data"]["id_comprador"]) ?></li>
                <li class="list-group-item"><strong>Vendedor:</strong> <?= htmlspecialchars($tramite["data"]["id_vendedor"]) ?></li>
                <li class="list-group-item"><strong>Detalles:</strong> <?= htmlspecialchars($tramite["data"]["detalles"]) ?></li>
            </ul>
            <div class="text-center mt-4">
                <a href="misTramites.php" class="btn btn-secondary">Volver a Mis Trámites</a>
            </div>
        </div>
    </div>
</body>
</html>