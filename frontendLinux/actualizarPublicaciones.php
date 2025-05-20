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
$apiUrl = "http://192.168.100.3:8080/automarket/publicaciones/" . $idPublicacion;
$response = file_get_contents($apiUrl);
$publicacion = json_decode($response, true);

if (!$publicacion || $publicacion["userId"] != $userId) {
    die('<div class="alert alert-danger text-center">❌ No tienes permiso para editar esta publicación.</div>');
}

// Manejo de errores si se envía el formulario
$errorMessage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        "userId" => $userId,
        "marca" => htmlspecialchars($_POST["marca"] ?? ""),
        "modelo" => htmlspecialchars($_POST["modelo"] ?? ""),
        "ano" => $_POST["ano"] ?? 0,
        "precio" => $_POST["precio"] ?? 0,
        "kilometraje" => $_POST["kilometraje"] ?? 0,
        "tipo_combustible" => htmlspecialchars($_POST["tipo_combustible"] ?? ""),
        "transmision" => htmlspecialchars($_POST["transmision"] ?? ""),
        "tamano_motor" => (float) ($_POST["tamano_motor"] ?? 0.0),
        "puertas" => $_POST["puertas"] ?? 0,
        "ultimo_dueno" => htmlspecialchars($_POST["ultimo_dueno"] ?? ""),
        "descripcion" => htmlspecialchars($_POST["descripcion"] ?? ""),
        "ubicacion" => htmlspecialchars($_POST["ubicacion"] ?? ""),
        "estado" => $_POST["estado"] ?? "Disponible"
    ];

    $updateUrl = "http://192.168.100.3:8080/automarket/publicaciones/editar/" . $idPublicacion;

    $ch = curl_init($updateUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $error = json_decode($response, true);

    if ($httpCode == 200) {
        echo '<div class="alert alert-success text-center">✅ Publicación actualizada exitosamente. Redirigiendo...</div>';
        header("Refresh: 2; URL=publicaciones.php");
        exit();
    } else {
        $errorMessage = "❌ Error al actualizar la publicación.";
        
        if (isset($error["message"])) {
            $errorMessage .= " " . htmlspecialchars($error["message"]);
        }

        echo '<div class="alert alert-danger text-center">' . $errorMessage . '</div>';
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
            <div class="mb-3">
                <label class="form-label">Kilometraje</label>
                <input type="number" class="form-control" name="kilometraje" value="<?= htmlspecialchars($publicacion['kilometraje']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo de Combustible</label>
                <input type="text" class="form-control" name="tipo_combustible" value="<?= htmlspecialchars($publicacion['tipo_combustible']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Transmisión</label>
                <input type="text" class="form-control" name="transmision" value="<?= htmlspecialchars($publicacion['transmision']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tamaño del Motor</label>
                <input type="text" class="form-control" name="tamano_motor" value="<?= htmlspecialchars($publicacion['tamano_motor']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Número de Puertas</label>
                <input type="number" class="form-control" name="puertas" value="<?= htmlspecialchars($publicacion['puertas']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Último Dueño</label>
                <input type="text" class="form-control" name="ultimo_dueno" value="<?= htmlspecialchars($publicacion['ultimo_dueno']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" required><?= htmlspecialchars($publicacion['descripcion']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Ubicación</label>
                <input type="text" class="form-control" name="ubicacion" value="<?= htmlspecialchars($publicacion['ubicacion']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-control" name="estado" required>
                    <option value="Disponible" <?= $publicacion['estado'] == "Disponible" ? "selected" : "" ?>>Disponible</option>
                    <option value="Vendido" <?= $publicacion['estado'] == "Vendido" ? "selected" : "" ?>>Vendido</option>
                    <option value="Reservado" <?= $publicacion['estado'] == "Reservado" ? "selected" : "" ?>>Reservado</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
            <a href="publicaciones.php" class="btn btn-secondary"> Volver</a>
        </form>
    </div>
</body>
</html>
