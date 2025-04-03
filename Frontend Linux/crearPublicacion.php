<?php  
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["user"]) || !isset($_SESSION["user"]["userId"])) {
    die('<div class="alert alert-danger text-center">⚠️ Debes iniciar sesión para publicar un auto.</div>');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION["user"]["userId"];
    
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
    
    $apiUrl = "http://192.168.100.3:8080/automarket/publicaciones/publicar";
    
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $error = json_decode($response, true);

    if ($httpCode == 201) {
        echo '<div class="alert alert-success text-center">✅ Publicación creada exitosamente. Redirigiendo...</div>';
        header("Refresh: 2; URL=perfil.php");
        exit();
    } else {
        $errorMessage = "❌ Error al crear la publicación.";
    
        // Verifica si hay un mensaje de error específico en la respuesta JSON
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
    <title>Crear Publicación | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">🚗 Crear Publicación</h2>
        <form method="POST">
            <!-- Campos del formulario -->
            <div class="mb-3">
                <label class="form-label">Marca</label>
                <input type="text" class="form-control" name="marca" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Modelo</label>
                <input type="text" class="form-control" name="modelo" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Año</label>
                <input type="number" class="form-control" name="ano" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input type="number" class="form-control" name="precio" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kilometraje</label>
                <input type="number" class="form-control" name="kilometraje" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo de Combustible</label>
                <input type="text" class="form-control" name="tipo_combustible" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Transmisión</label>
                <input type="text" class="form-control" name="transmision" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tamaño del Motor</label>
                <input type="text" class="form-control" name="tamano_motor" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Puertas</label>
                <input type="number" class="form-control" name="puertas" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Último Dueño</label>
                <input type="text" class="form-control" name="ultimo_dueno" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Ubicación</label>
                <input type="text" class="form-control" name="ubicacion" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-control" name="estado" required>
                    <option value="Disponible">Disponible</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">📢 Publicar</button>
            <a href="publicaciones.php" class="btn btn-secondary">🔙 Volver</a>
        </form>
    </div>
</body>
</html>
