<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $api_url = "http://localhost:8081/automarketuao/users/read/" . urlencode($email);
    
    // Realizar la solicitud a la API
    $response = file_get_contents($api_url);
    
    // Verificar si la respuesta de la API fue válida
    if ($response === FALSE) {
        die('<div class="alert alert-danger text-center">❌ Error al conectar con la API.</div>');
    }

    // Decodificar la respuesta JSON
    $user = json_decode($response, true);

    // Comprobar si se ha encontrado al usuario
    if ($user) {
        // Ya no verificamos la contraseña encriptada, la comparamos tal cual
        echo "Contraseña recibida de la API: " . $user["password"];  // Verifica la contraseña que se recibió
    } else {
        echo "No se encontró el usuario con el email proporcionado.";
    }

    // Verificar si la contraseña ingresada es correcta
    if ($user && $password === $user["password"]) {
        // Almacenar la información del usuario en la sesión
        $_SESSION["user"] = $user;
        $_SESSION["userId"] = $user["userId"]; // Guardar userId en la sesión
        
        // Redirigir según el rol del usuario
        if ($user["role"] === "admin") {
            header("Location: admin.php");
        } else {
            header("Location: perfil.php");
        }
        exit();
    } else {
        // Si no coincide la contraseña
        $error = "❌ Email o contraseña incorrectos.";
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
            <?php if (!isset($_SESSION["user"])): ?>
                <h2 class="text-center mb-4">Iniciar Sesión</h2>
                <?php if (isset($error)) echo '<div class="alert alert-danger text-center">' . $error . '</div>'; ?>
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
            <?php endif; ?>
            <!-- Botón de regreso a index.php -->
            <div class="text-center mt-3">
                <a href="index.php" class="btn btn-secondary">Regresar a Inicio</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
