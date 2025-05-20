<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idPublicacion"])) {
    $userId = $_SESSION["user"]["userId"];
    $idPublicacion = $_POST["idPublicacion"];
    
    $apiUrl = "http://192.168.100.3:8080/automarket/publicaciones/eliminar/" . $idPublicacion;
    
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        header("Location: publicaciones.php");
        exit();
    } else {
        echo '<div class="alert alert-danger text-center">❌ Error al eliminar la publicación. Código HTTP: ' . $httpCode . '</div>';
    }
}

// Verificar si hay un ID de publicación en la URL
if (!isset($_GET["idPublicacion"])) {
    header("Location: publicaciones.php");
    exit();
}
$idPublicacion = $_GET["idPublicacion"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Publicación | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4 text-center">
            <h2 class="mb-4">❌ Eliminar Publicación</h2>
            <p>¿Estás seguro de que deseas eliminar esta publicación? Esta acción no se puede deshacer.</p>
            <form method="POST">
                <input type="hidden" name="idPublicacion" value="<?php echo $idPublicacion; ?>">
                <button type="submit" class="btn btn-danger">🗑️ Eliminar</button>
                <a href="publicaciones.php" class="btn btn-secondary">🔙 Cancelar</a>
            </form>
        </div>
    </div>
</body>
</html>
