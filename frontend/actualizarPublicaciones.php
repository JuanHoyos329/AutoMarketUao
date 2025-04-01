<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user"]) || !isset($_SESSION["user"]["userId"])) {
    die('<div class="alert alert-danger text-center">⚠️ Debes iniciar sesión para actualizar una publicación.</div>');
}

$userId = $_SESSION["user"]["userId"];
$idPublicacion = $_GET['idPublicacion'] ?? '';

if (!$idPublicacion) {
    die('<div class="alert alert-danger text-center">❌ Error: No se ha proporcionado una publicación válida.</div>');
}

// Obtener los datos de la publicación desde la API
$apiUrl = "http://localhost:8080/automarket/publicaciones/" . $idPublicacion;
$response = file_get_contents($apiUrl);
$publicacion = json_decode($response, true);

if (!$publicacion || $publicacion["userId"] != $userId) {
    die('<div class="alert alert-danger text-center">❌ No tienes permiso para editar esta publicación.</div>');
}

// Procesar la actualización si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        "userId" => $userId,
        "marca" => $_POST["marca"] ?? $publicacion["marca"],
        "modelo" => $_POST["modelo"] ?? $publicacion["modelo"],
        "ano" => $_POST["ano"] ?? $publicacion["ano"],
        "precio" => $_POST["precio"] ?? $publicacion["precio"],
        "kilometraje" => $_POST["kilometraje"] ?? $publicacion["kilometraje"],
        "tipo_combustible" => $_POST["tipo_combustible"] ?? $publicacion["tipo_combustible"],
        "transmision" => $_POST["transmision"] ?? $publicacion["transmision"],
        "tamano_motor" => $_POST["tamano_motor"] ?? $publicacion["tamano_motor"],
        "puertas" => $_POST["puertas"] ?? $publicacion["puertas"],
        "ultimo_dueno" => $_POST["ultimo_dueno"] ?? $publicacion["ultimo_dueno"],
        "descripcion" => $_POST["descripcion"] ?? $publicacion["descripcion"],
        "ubicacion" => $_POST["ubicacion"] ?? $publicacion["ubicacion"],
        "estado" => $_POST["estado"] ?? $publicacion["estado"]
    ];

    $updateUrl = "http://localhost:8080/automarket/publicaciones/editar/" . $idPublicacion;
    
    $ch = curl_init($updateUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        header("Location: publicaciones.php");
        exit();
    } else {
        echo '<div class="alert alert-danger text-center">❌ Error al actualizar la publicación. Código HTTP: ' . $httpCode . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Publicación | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">✏️ Actualizar Publicación</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Marca</label>
                <input type="text" class="form-control" name="marca" value="<?= htmlspecialchars($publicacion['marca']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Modelo</label>
                <input type="text" class="form-control" name="modelo" value="<?= htmlspecialchars($publicacion['modelo']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Año</label>
                <input type="number" class="form-control" name="ano" value="<?= htmlspecialchars($publicacion['ano']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input type="number" class="form-control" name="precio" value="<?= htmlspecialchars($publicacion['precio']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
            <a href="publicaciones.php" class="btn btn-secondary">🔙 Volver</a>
        </form>
    </div>
</body>
</html>
