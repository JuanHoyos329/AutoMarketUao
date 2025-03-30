<?php
$api_url = "http://localhost:8080/automarket/publicaciones/listarPublicaciones";
$response = file_get_contents($api_url);
$publicaciones = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicaciones | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">🚗 Autos Publicados</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($publicaciones)): ?>
                    <?php foreach ($publicaciones as $auto): ?>
                        <tr>
                            <td><?= htmlspecialchars($auto["marca"]) ?></td>
                            <td><?= htmlspecialchars($auto["modelo"]) ?></td>
                            <td><?= htmlspecialchars($auto["ano"]) ?></td>
                            <td>$<?= number_format($auto["precio"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay autos publicados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="perfil.php" class="btn btn-secondary">🔙 Volver</a>
    </div>
</body>
</html>