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
    <script>
        function confirmarEliminacion() {
            return confirm("⚠️ ¡Atención! Esta acción es irreversible. ¿Estás seguro de que deseas eliminar tu cuenta?");
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Perfil de Usuario</h2>
                <a href="eliminarUsuario.php?username=<?= $user["username"] ?>" class="btn btn-danger btn-sm" onclick="return confirmarEliminacion();">Eliminar Cuenta</a>
            </div>
            
            <hr>

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

            <!-- 📌 Botones organizados en dos columnas -->
            <div class="d-flex justify-content-center mt-4">
                <div class="me-2">
                    <a href="actualizarUsuario.php?username=<?= $user["username"] ?>" class="btn btn-warning">Editar Información</a>
                </div>
                <div>
                    <a href="publicaciones.php" class="btn btn-primary">Ver Autos Publicados</a>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <div class="me-2">
                    <a href="crearPublicacion.php" class="btn btn-success">Crear Publicación</a>
                </div>
                <div>
                    <a href="misTramites.php" class="btn btn-info">Mis Trámites</a>
                </div>
            </div>

            <!-- 🔻 Botón Cerrar Sesión centrado al final -->
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-secondary">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
