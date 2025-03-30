<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: index.php");
    exit();
}

// Obtener la lista de usuarios desde la API
$api_url = "http://localhost:8080/automarketuao/users/all";
$response = file_get_contents($api_url);
$usuarios = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger text-center" id="error-message">
        <?= htmlspecialchars($_GET['error']) ?>
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('error-message').style.display = 'none';
        }, 5000); // El mensaje desaparecerá después de 5 segundos
    </script>
<?php endif; ?>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center mb-4">Panel de Administración</h2>
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th> <!-- 🔹 Nueva columna para userId -->
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
                            <td><?= htmlspecialchars($usuario["userId"]) ?></td> <!-- 🔹 Mostrar userId -->
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
                                    <input type="hidden" name="username" value="<?= htmlspecialchars($usuario["username"]) ?>"> <!-- Usar username en lugar de email -->
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?')">Eliminar</button>
                                </form>
                            </td>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-secondary">Cerrar Sesión</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>