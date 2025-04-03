<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: index.php");
    exit();
}

// Obtener la lista de usuarios desde la API
$api_url = "http://192.168.100.3:8081/automarketuao/users/all";
$response = file_get_contents($api_url);
$usuarios = json_decode($response, true);

// Verificar si hay mensajes de éxito o error en la sesión
$mensaje = "";
if (isset($_SESSION["mensaje"])) {
    $mensaje = $_SESSION["mensaje"];
    unset($_SESSION["mensaje"]); // Limpiar mensaje después de mostrarlo
}

$user = $_SESSION["user"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center mb-4">Panel de Administración</h2>

            <!-- Mensaje de éxito o error -->
            <?= $mensaje ?>

            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Usuario</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario["userId"]) ?></td>
                            <td><?= htmlspecialchars($usuario["name"]) ?></td>
                            <td><?= htmlspecialchars($usuario["last_name"]) ?></td>
                            <td><?= htmlspecialchars($usuario["email"]) ?></td>
                            <td><?= htmlspecialchars($usuario["username"]) ?></td>
                            <td><?= htmlspecialchars($usuario["phone"]) ?></td>
                            <td>
                                <form action="cambiarRol.php" method="post" class="d-inline">
                                    <input type="hidden" name="email" value="<?= $usuario["email"] ?>">
                                    <select name="role" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                        <option value="user" <?= $usuario["role"] === "user" ? "selected" : "" ?>>Usuario</option>
                                        <option value="admin" <?= $usuario["role"] === "admin" ? "selected" : "" ?>>Administrador</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a href="actualizarUsuario.php?email=<?= urlencode($usuario["email"]) ?>" class="btn btn-warning btn-sm">Editar</a>
                                <form action="eliminarUsuario.php" method="post" class="d-inline">
                                    <input type="hidden" name="username" value="<?= htmlspecialchars($usuario["username"]) ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Botón para editar la información personal -->
            <div class="text-center mt-3">
                <a href="actualizarUsuario.php?username=<?= $user["username"] ?>" class="btn btn-warning">Editar Información</a>
            </div>

            <!-- Botón para eliminar cuenta -->
            <div class="text-center mt-3">
                <a href="eliminarUsuario.php?username=<?= $user["username"] ?>" class="btn btn-danger">Eliminar Cuenta</a>
            </div>

            <!-- Botón para cerrar sesión -->
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

            <!-- Botón para acceder a Mis Trámites -->
            <div class="text-center mt-3">
                <a href="misTramites.php" class="btn btn-info">Mis Trámites</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
