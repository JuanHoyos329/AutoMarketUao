<?php
session_start();

$mensaje = ""; // Variable para almacenar mensajes de error o éxito

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $api_url = "http://localhost:8081/automarketuao/users/read/" . urlencode($email);
    
    // Inicializar cURL para hacer la solicitud a la API
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Tiempo máximo de espera de 10 segundos

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        $mensaje = '<div class="alert alert-danger text-center">❌ Error al conectar con la API.</div>';
    } else {
        $user = json_decode($response, true);

        if ($http_code !== 200) {
            $mensaje = isset($user["error"]) ? 
                '<div class="alert alert-danger text-center">❌ ' . htmlspecialchars($user["error"]) . '</div>' :
                '<div class="alert alert-danger text-center">❌ Error desconocido en la autenticación.</div>';
        } elseif (!$user || !isset($user["password"])) {
            $mensaje = '<div class="alert alert-danger text-center">❌ No se encontró el usuario con el email proporcionado.</div>';
        } elseif ($password === $user["password"]) {
            $_SESSION["user"] = $user;
            $_SESSION["userId"] = $user["userId"]; 

            // Redirigir según el rol del usuario
            header("Location: " . ($user["role"] === "admin" ? "admin.php" : "perfil.php"));
            exit();
        } else {
            $mensaje = '<div class="alert alert-danger text-center">❌ Email o contraseña incorrectos.</div>';
        }
    }
}

// Cerrar sesión si el usuario vuelve a index.php
if (basename($_SERVER["PHP_SELF"]) == "index.php") {
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center mb-4">Iniciar Sesión</h2>

            <!-- Mostrar mensaje de error o éxito arriba -->
            <?= $mensaje ?>

            <form action="login.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" placeholder="Ingrese su correo" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="Ingrese su contraseña" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <a href="crearUsuario.php" class="btn btn-success">Crear Cuenta</a>
            </div>
            <div class="text-center mt-3">
                <a href="index.php" class="btn btn-secondary">Regresar a Inicio</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
