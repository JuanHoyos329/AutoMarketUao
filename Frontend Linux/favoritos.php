<?php
session_start();

if (!isset($_SESSION["user"]) || !isset($_SESSION["user"]["userId"])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["user"]["userId"];
$api_url = "http://192.168.100.3:3002/favoritos/$userId";
$response = @file_get_contents($api_url);
$favoritos = $response ? json_decode($response, true)["data"] ?? [] : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Favoritos | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">❤️ Mis Favoritos</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Precio</th>
                    <th>Eliminar favorito</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($favoritos)): ?>
                    <?php foreach ($favoritos as $fav): ?>
                        <tr>
                            <td><?= htmlspecialchars($fav["marca"]) ?></td>
                            <td><?= htmlspecialchars($fav["modelo"]) ?></td>
                            <td><?= htmlspecialchars($fav["ano"]) ?></td>
                            <td>$<?= number_format($fav["precio"]) ?></td>
                            <td>
                                <form method="POST" action="eliminarFavorito.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $fav["id"] ?>">
                                    <button type="submit" class="btn btn-danger" title="Eliminar de favoritos">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No tienes autos en favoritos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="publicaciones.php" class="btn btn-secondary">🔙 Volver a publicaciones</a>
    </div>
</body>
</html>
