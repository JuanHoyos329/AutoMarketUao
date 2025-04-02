<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
$isAdmin = ($user["role"] === "admin"); // Verificar si es administrador
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center mb-4">Perfil de Usuario</h2>
            <ul class="list-group">
                <?php foreach ($user as $key => $value): ?>
                    <?php 
                        // Ocultar "password", "userId" y "role" si el usuario no es admin
                        if ($key !== "password" && $key !== "userId" && ($key !== "role" || $isAdmin)) : 
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><?= ucfirst($key) ?>:</strong> <?= htmlspecialchars($value) ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <!-- 🔧 Botón único para editar la información -->
            <div class="text-center mt-4">
                <a href="actualizarUsuario.php?username=<?= $user["username"] ?>" class="btn btn-warning">Editar Información</a>
            </div>

            <div class="text-center mt-3">
                <a href="eliminarUsuario.php?username=<?= $user["username"] ?>" class="btn btn-danger">Eliminar Cuenta</a>
            </div>

            <div class="text-center mt-3">
                <a href="index.php" class="btn btn-secondary">Cerrar Sesión</a>
            </div>

            <!-- Botón para ver autos publicados -->
            <div class="text-center mt-3">
                <a href="publicaciones.php" class="btn btn-primary">Ver Autos Publicados</a>
            </div>

            <!-- Botón para crear una nueva publicación -->
            <div class="text-center mt-3">
                <a href="crearPublicacion.php" class="btn btn-success">Crear Publicación</a>
            </div>

            <!-- ✅ Nuevo Botón para acceder a Mis Trámites -->
            <div class="text-center mt-3">
                <a href="misTramites.php" class="btn btn-info">Mis Trámites</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>